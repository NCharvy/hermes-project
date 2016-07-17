<?php

namespace Orange\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SousTypologie
 *
 * @ORM\Table(name="dbdevco_sous_typologie")
 * @ORM\Entity(repositoryClass="Orange\HomeBundle\Repository\SousTypologieRepository")
 */
class SousTypologie
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Orange\HomeBundle\Entity\Typologie")
     * @ORM\JoinColumn(nullable=false)
     */
    private $typologie;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="route", type="string", length=255)
     */
    private $route;


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
     * Set libelle
     *
     * @param string $libelle
     * @return SousTypologie
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set route
     *
     * @param string $route
     * @return SousTypologie
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return string 
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set typologie
     *
     * @param \Orange\HomeBundle\Entity\Typologie $typologie
     * @return SousTypologie
     */
    public function setTypologie(\Orange\HomeBundle\Entity\Typologie $typologie)
    {
        $this->typologie = $typologie;

        return $this;
    }

    /**
     * Get typologie
     *
     * @return \Orange\HomeBundle\Entity\Typologie 
     */
    public function getTypologie()
    {
        return $this->typologie;
    }
}
