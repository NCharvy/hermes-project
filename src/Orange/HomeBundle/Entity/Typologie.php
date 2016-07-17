<?php

namespace Orange\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Typologie
 *
 * @ORM\Table(name="dbdevco_typologie")
 * @ORM\Entity(repositoryClass="Orange\HomeBundle\Repository\TypologieRepository")
 */
class Typologie
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
     * @ORM\ManyToOne(targetEntity="Orange\HomeBundle\Entity\Classification", inversedBy="typologies")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    private $classification;

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
     * @var bool
     *
     * @ORM\Column(name="sublevel", type="boolean", nullable=true)
     */
    private $sublevel;

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
     * @return Typologie
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
     * @return Typologie
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
     * Set classification
     *
     * @param \Orange\HomeBundle\Entity\Classification $classification
     * @return Typologie
     */
    public function setClassification(\Orange\HomeBundle\Entity\Classification $classification)
    {
        $this->classification = $classification;

        return $this;
    }

    /**
     * Get classification
     *
     * @return \Orange\HomeBundle\Entity\Classification 
     */
    public function getClassification()
    {
        return $this->classification;
    }

    /**
     * Set sublevel
     *
     * @param boolean $sublevel
     * @return Typologie
     */
    public function setSublevel($sublevel)
    {
        $this->sublevel = $sublevel;

        return $this;
    }

    /**
     * Get sublevel
     *
     * @return boolean 
     */
    public function getSublevel()
    {
        return $this->sublevel;
    }
}
