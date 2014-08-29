<?php

namespace Megalo\Bundle\SecurityBundle\Services;

use \Megalo\Bundle\SecurityBundle\Entity\LocatorEntity;
use \Megalo\Bundle\SecurityBundle\Entity\Log;

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
      $ipAdress = '19.226.904.800';
    }

    $countryCode = file_get_contents('http://geoip1.maxmind.com/a?l=JmgjCCOsPHOO&i=' . $ipAdress);

    $locatorEntity = $this->em->getRepository('MegaloSecurityBundle:LocatorEntity')->findOneBy(array('name' => $name));

    if (is_object($locatorEntity)) {
      if ($locatorEntity->getLastCountry() != $countryCode) {
        $message = 'The user : ' . $name . ' was connected in another country. Today : ' . $countryCode . ' Before : ' . $locatorEntity->getLastCountry();
        mail('yannericdevars@gmail.com', 'Security alert', $message);

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

}

?>
