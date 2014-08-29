<?php

namespace Megalo\Bundle\SecurityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LocatorEntity
 *
 * @ORM\Table(name="security_locator")
 * @ORM\Entity
 */
class LocatorEntity
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="lastCountry", type="string", length=255)
     */
    private $lastCountry;

    /**
     * @var string
     *
     * @ORM\Column(name="lastConnection", type="string", length=255)
     */
    private $lastConnection;


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
     * Set name
     *
     * @param string $name
     * @return LocatorEntity
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set lastCountry
     *
     * @param string $lastCountry
     * @return LocatorEntity
     */
    public function setLastCountry($lastCountry)
    {
        $this->lastCountry = $lastCountry;

        return $this;
    }

    /**
     * Get lastCountry
     *
     * @return string 
     */
    public function getLastCountry()
    {
        return $this->lastCountry;
    }

    /**
     * Set lastConnection
     *
     * @param string $lastConnection
     * @return LocatorEntity
     */
    public function setLastConnection($lastConnection)
    {
        $this->lastConnection = $lastConnection;

        return $this;
    }

    /**
     * Get lastConnection
     *
     * @return string 
     */
    public function getLastConnection()
    {
        return $this->lastConnection;
    }
}
