<?php

namespace Megalo\Bundle\SecurityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Attempt
 *
 * @ORM\Table(name="security_attempts")
 * @ORM\Entity
 */
class Attempt
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=255)
     */
    private $login;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbAttempts", type="integer")
     */
    private $nbAttempts;

    /**
     * @var integer
     *
     * @ORM\Column(name="lastAttempt", type="bigint")
     */
    private $lastAttempt;




    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set login
     *
     * @param string $login
     * @return Attempt
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set nbAttempts
     *
     * @param integer $nbAttempts
     * @return Attempt
     */
    public function setNbAttempts($nbAttempts)
    {
        $this->nbAttempts = $nbAttempts;

        return $this;
    }

    /**
     * Get nbAttempts
     *
     * @return integer 
     */
    public function getNbAttempts()
    {
        return $this->nbAttempts;
    }

    /**
     * Set lastAttempt
     *
     * @param integer $lastAttempt
     * @return Attempt
     */
    public function setLastAttempt($lastAttempt)
    {
        $this->lastAttempt = $lastAttempt;

        return $this;
    }

    /**
     * Get lastAttempt
     *
     * @return integer 
     */
    public function getLastAttempt()
    {
        return $this->lastAttempt;
    }


}
