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
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     */
    private $classification;

    /**
    *
    * @ORM\OneToMany(targetEntity="Orange\HomeBundle\Entity\Fichier", mappedBy="typologie", orphanRemoval=true)
    * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
    */
    private $fichiers;

    /**
    *
    * @ORM\OneToMany(targetEntity="Orange\HomeBundle\Entity\SousTypologie", mappedBy="typologie", orphanRemoval=true)
    * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
    */
    private $soustypologies;

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
     * Constructor
     */
    public function __construct()
    {
        $this->fichiers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->soustypologies = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add fichiers
     *
     * @param \Orange\HomeBundle\Entity\Fichier $fichiers
     * @return Typologie
     */
    public function addFichier(\Orange\HomeBundle\Entity\Fichier $fichiers)
    {
        $this->fichiers[] = $fichiers;

        return $this;
    }

    /**
     * Remove fichiers
     *
     * @param \Orange\HomeBundle\Entity\Fichier $fichiers
     */
    public function removeFichier(\Orange\HomeBundle\Entity\Fichier $fichiers)
    {
        $this->fichiers->removeElement($fichiers);
    }

    /**
     * Get fichiers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFichiers()
    {
        return $this->fichiers;
    }

    /**
     * Add soustypologies
     *
     * @param \Orange\HomeBundle\Entity\SousTypologie $soustypologies
     * @return Typologie
     */
    public function addSoustypology(\Orange\HomeBundle\Entity\SousTypologie $soustypologies)
    {
        $this->soustypologies[] = $soustypologies;

        return $this;
    }

    /**
     * Remove soustypologies
     *
     * @param \Orange\HomeBundle\Entity\SousTypologie $soustypologies
     */
    public function removeSoustypology(\Orange\HomeBundle\Entity\SousTypologie $soustypologies)
    {
        $this->soustypologies->removeElement($soustypologies);
    }

    /**
     * Get soustypologies
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSoustypologies()
    {
        return $this->soustypologies;
    }
}
