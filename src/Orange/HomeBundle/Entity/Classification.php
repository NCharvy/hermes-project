<?php

namespace Orange\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Classification
 *
 * @ORM\Table(name="dbdevco_classification")
 * @ORM\Entity(repositoryClass="Orange\HomeBundle\Repository\ClassificationRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Classification
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
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="route", type="string", length=255)
     */
    private $route;

    /**
     * @var UploadedFile
     */
    private $image;

    /**
     * @var string
     */
    private $tmpFilename;

    /**
     * @var string
     *
     * @ORM\Column(name="visuel", type="string", length=255)
     */
    private $visuel;

    /**
     * @ORM\OneToMany(targetEntity="Orange\HomeBundle\Entity\Typologie", mappedBy="classification", orphanRemoval=true)
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    private $typologies;

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
     * @return Classification
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
     * @return Classification
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
     * Set visuel
     *
     * @param string $visuel
     * @return Classification
     */
    public function setVisuel($visuel)
    {
        $this->visuel = $visuel;

        return $this;
    }

    /**
     * Get visuel
     *
     * @return string 
     */
    public function getVisuel()
    {
        return $this->visuel;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Classification
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->typologies = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add typologies
     *
     * @param \Orange\HomeBundle\Entity\Typologie $typologies
     * @return Classification
     */
    public function addTypology(\Orange\HomeBundle\Entity\Typologie $typologies)
    {
        $this->typologies[] = $typologies;

        return $this;
    }

    /**
     * Remove typologies
     *
     * @param \Orange\HomeBundle\Entity\Typologie $typologies
     */
    public function removeTypology(\Orange\HomeBundle\Entity\Typologie $typologies)
    {
        $this->typologies->removeElement($typologies);
    }

    /**
     * Get typologies
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTypologies()
    {
        return $this->typologies;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if(null === $this->image)
        {
            return;
        }

        $this->visuel = $this->image->getClientOriginalName();
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        $add = $this->visuel;

        if(null === $this->image){
            return;
        }

        if(null !== $this->tmpFilename){
            $oldFile = $this->getUploadRootDir() . '/' . $this->tmpFilename;
            if(file_exists($oldFile)){
                unlink($oldFile);
            }
        }

        if(file_exists($this->getUploadRootDir() . '/' . $this->visuel)){
            $ch = explode(".", $this->lien);
            $add = $ch[0] . " (copie)." . $ch[1];
        }

        $this->image->move(
            $this->getUploadRootDir(),
            $add
        );
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        $this->tmpFilename = $this->getUploadRootDir() . '/' . $this->visuel;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if(file_exists($this->tmpFilename)){
            unlink($this->tmpFilename);
        }
    }

    public function getUploadRootDir()
    {
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    public function getUploadDir()
    {
        return 'uploads/visuels';
    }

    /**
     * Set image
     *
     * @param UploadedFile $image
     * @return Classification
     */
    public function setImage($image = null)
    {
        $this->image = $image;
        if(null !== $this->visuel)
        {
            $this->tmpFilename = $this->visuel;
        }

        return $this;
    }

    /**
     * Get image
     *
     * @return UploadedFile
     */
    public function getImage()
    {
        return $this->image;
    }
}
