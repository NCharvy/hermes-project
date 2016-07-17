<?php

namespace Orange\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Fichier
 *
 * @ORM\Table(name="dbdevco_fichier")
 * @ORM\Entity(repositoryClass="Orange\HomeBundle\Repository\FichierRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Fichier
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
     * @ORM\ManyToOne(targetEntity="Orange\HomeBundle\Entity\SousTypologie")
     * @ORM\JoinColumn(nullable=true)
     */
    private $soustypologie;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Orange\HomeBundle\Entity\Typologie")
     * @ORM\JoinColumn(nullable=true)
     */
    private $typologie;

    /**
     * @var UploadedFile
     */
    private $file;

    /**
     * @var string
     */
    private $tmpFilename;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="lien", type="string", length=255)
     */
    private $lien;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;


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
     * Set nom
     *
     * @param string $nom
     * @return Fichier
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Fichier
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set soustypologie
     *
     * @param \Orange\HomeBundle\Entity\SousTypologie $soustypologie
     * @return Fichier
     */
    public function setSoustypologie(\Orange\HomeBundle\Entity\SousTypologie $soustypologie)
    {
        $this->soustypologie = $soustypologie;

        return $this;
    }

    /**
     * Get soustypologie
     *
     * @return \Orange\HomeBundle\Entity\SousTypologie 
     */
    public function getSoustypologie()
    {
        return $this->soustypologie;
    }

    /**
     * Set typologie
     *
     * @param \Orange\HomeBundle\Entity\Typologie $typologie
     * @return Fichier
     */
    public function setTypologie(\Orange\HomeBundle\Entity\Typologie $typologie = null)
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

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if(null === $this->file)
        {
            return;
        }

        $this->lien = $this->file->getClientOriginalName();
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if(null === $this->file){
            return;
        }

        if(null !== $this->tmpFilename){
            $oldFile = $this->getUploadRootDir() . '/' . $this->tmpFilename;
            if(file_exists($oldFile)){
                unlink($oldFile);
            }
        }

        $this->file->move(
            $this->getUploadRootDir(),
            $this->lien
        );
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        $this->tmpFilename = $this->getUploadRootDir() . '/' . $this->lien;
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
        return 'uploads/resources';
    }

    /**
     * Set lien
     *
     * @param string $lien
     * @return Fichier
     */
    public function setLien($lien)
    {
        $this->lien = $lien;

        return $this;
    }

    /**
     * Get lien
     *
     * @return string 
     */
    public function getLien()
    {
        return $this->lien;
    }

    /**
     * Set file
     *
     * @param UploadedFile $file
     * @return Fichier
     */
    public function setFile($file = null)
    {
        $this->file = $file;
        if(null !== $this->lien)
        {
            $this->tmpFilename = $this->lien;
            //$this->nom_devis = null;
        }

        return $this;
    }

    /**
     * Get file
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
}
