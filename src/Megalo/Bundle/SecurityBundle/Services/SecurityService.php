<?php

namespace Megalo\Bundle\SecurityBundle\Services;

use \Megalo\Bundle\SecurityBundle\Entity\LocatorEntity;
use \Megalo\Bundle\SecurityBundle\Entity\Log;
use Megalo\Bundle\SecurityBundle\Entity\Attempt;

/**
 * Description of SecurityService
 *
 * @author ydevars
 */
class SecurityService
{

  private $container;
  private $em;

  /**
   * Construceur de classe
   * @param Container     $container Le container de l'application
   * @param EntityManager $em        L'entity manager
   */
  public function __construct($container, $em)
  {
    $this->container = $container;
    $this->em = $em;
  }

  /**
   * Alerte si l'utilisateur se connecte d'un autre pays
   * Et stocke le pays d'origine de l'utilisateur
   * @param string $name Nom à sauvegarder (login par ex)
   * @param string $env  L'environnement
   */
  public function checkIpValid($name, $env = 'prod')
  {
    // Bouchon pour gerer l'environnement
    if ($env === 'dev') {
      $ipAdress = '89.226.90.80';
    }

    $countryCode = file_get_contents('http://geoip1.maxmind.com/a?l=JmgjCCOsPHOO&i=' . $ipAdress);


    $locatorEntity = $this->em->getRepository('MegaloSecurityBundle:LocatorEntity')->findOneBy(array('name' => $name));

    if (is_object($locatorEntity)) {
      if ($locatorEntity->getLastCountry() != $countryCode) {
        $message = 'The user : ' . $name . ' was connected in another country. Today : ' . $countryCode . ' Before : ' . $locatorEntity->getLastCountry();
        mail('yannericdevars@gmail.com', 'Security alert', $message);

        var_dump("Autre pays");

        $locatorEntity->setLastCountry($countryCode);
        $locatorEntity->setLastConnection(date("Y-m-d H:i:s"));

        $this->em->persist($locatorEntity);
        $this->em->flush();
      }

      if ($locatorEntity->getLastCountry() == $countryCode) {
        $locatorEntity->setLastConnection(date("Y-m-d H:i:s"));

        $this->em->persist($locatorEntity);
        $this->em->flush();
      }
    }

    if (!is_object($locatorEntity)) {
      $locatorEntity = new LocatorEntity();
      $locatorEntity->setName($name);
      $locatorEntity->setLastCountry($countryCode);
      $locatorEntity->setLastConnection(date("Y-m-d H:i:s"));

      $this->em->persist($locatorEntity);
      $this->em->flush();
    }
  }

  /**
   * Fonction de log
   * @param string $name  Nom de l'utilisateur
   * @param string $value Nouvelle valeur
   */
  public function logChange($name, $value)
  {
    $log = new Log();
    $log->setName($name);
    $log->setValue($value);
    $log->setModif(date("Y-m-d H:i:s"));

    $this->em->persist($log);
    $this->em->flush();
  }

  /**
   * Fonction qui vérifie si l'utilisateur peut tenter la connexion
   * @param string $login Le login de l'utilisateur
   * 
   * @return true S'il a le droit de tenter
   */
  public function attempt($login)
  {

    $autorise = false;

    $attempt = $this->em->getRepository('MegaloSecurityBundle:Attempt')->findOneBy(array('login' => $login));

    if (is_object($attempt)) {
      // L'utilisateur est dans la table

      $now = time();
      $lastAttempt = $attempt->getLastAttempt();

      // Si l'utilisateur a tenté de se loguer plus de 3 fois
      if ($attempt->getNbAttempts() > 3) {

        // On verifie le temps
        $lastAttempt = $attempt->getLastAttempt();
        $now = time();

        $diff = $now - $lastAttempt;

        // Si la connexion date d'il y a une heure on accepte la tentative
        if ($diff >= 60 * 60) {
          $autorise = true;
        }
      }

      if ($attempt->getNbAttempts() <= 3) {
        $autorise = true;
      }
    }

    if (!is_object($attempt)) {
      $autorise = true;
    }

    if ($attempt->getRedefine()) {
      $autorise = $this->verifyRedefine($attempt);
    }


    return $autorise;
  }

  private function verifyRedefine($attempt)
  {
    $isAut = true;
    if ($attempt->getRedefine()) {
      $lastAttempt = $attempt->getLastAttempt();
      $now = time();

      $diff = $now - $lastAttempt;

      if ($diff > 60 * 5) {
        $isAut = false;
      }
    }

    return $isAut;
  }

  /**
   * Fonction a appeler si le login a échoué
   * @param string $login Login de l'utilisateur
   */
  public function loginFail($login)
  {
    $attempt = $this->em->getRepository('MegaloSecurityBundle:Attempt')->findOneBy(array('login' => $login));

    if (is_object($attempt)) {
      $oldAttemps = $attempt->getNbAttempts();
      $oldAttemps++;
      $attempt->setNbAttempts($oldAttemps);
      $attempt->setLastAttempt(time());
    }

    if (!is_object($attempt)) {
      $attempt = new Attempt();
      $attempt->setLogin($login);
      $attempt->setLastAttempt(time());
      $attempt->setNbAttempts(1);
    }

    $this->em->persist($attempt);
    $this->em->flush();
  }

  /**
   * Fonction a appeler si le login a reussi
   * @param string $login Le login de l'utilisateur
   */
  public function loginSuccess($login)
  {
    $attempt = $this->em->getRepository('MegaloSecurityBundle:Attempt')->findOneBy(array('login' => $login));

    if (is_object($attempt)) {
      $this->em->remove($attempt);
      $this->em->flush();
    }
  }

  /**
   * Fonction a appeler si l'utilisateur a redefini son mot de passe
   * @param string $login Le login de l'utilisateur
   */
  public function loginRedefineByUser($login)
  {

    $attempt = $this->em->getRepository('MegaloSecurityBundle:Attempt')->findOneBy(array('login' => $login));

    if (is_object($attempt)) {
      $this->em->remove($attempt);
      $this->em->flush();
    }
  }

  /**
   * Fonction a appeler si l'admin a redefini le mot de passe d'un utilisateur
   * @param string $login Le login de l'utilisateur
   */
  public function loginRedefineByAdmin($login)
  {
    
    $attempt = $this->em->getRepository('MegaloSecurityBundle:Attempt')->findOneBy(array('login' => $login));

    if (!is_object($attempt)) {
      $attempt = new Attempt();
      $attempt->setLogin($login);
    }
    
    if (is_object($attempt)) {
      $attempt->setLastAttempt(time());
      $attempt->setNbAttempts(1);
      $attempt->setRedefine(true);
      $this->em->persist($attempt);
      $this->em->flush();
    }
  }

}

