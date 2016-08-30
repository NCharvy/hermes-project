<?php

namespace Orange\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Type
 *
 * @ORM\Table(name="dbdevco_type")
 * @ORM\Entity(repositoryClass="Orange\HomeBundle\Repository\TypeRepository")
 */
class Type
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
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

    /**
     *
     * @ORM\OneToMany(targetEntity="Orange\HomeBundle\Entity\Fichier", mappedBy="type")
     * @ORM\JoinColumn(nullable=true)
     */
    private $fichiers;


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
     * @return Type
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
     * Constructor
     */
    public function __construct()
    {
        $this->fichiers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add fichiers
     *
     * @param \Orange\HomeBundle\Entity\Fichier $fichiers
     * @return Type
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
}
