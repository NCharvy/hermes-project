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
     * @ORM\ManyToOne(targetEntity="Orange\HomeBundle\Entity\SousTypologie", inversedBy="fichiers")
     * @ORM\JoinColumn(nullable=true)
     */
    private $soustypologie;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Orange\HomeBundle\Entity\Typologie", inversedBy="fichiers")
     * @ORM\JoinColumn(nullable=true)
     */
    private $typologie;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Orange\HomeBundle\Entity\Type", inversedBy="fichiers")
     * @ORM\JoinColumn(nullable=true)
     */
    private $type;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Orange\HomeBundle\Entity\Extension")
     * @ORM\JoinColumn(nullable=true)
     */
    private $extension;

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
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="date", nullable=true)
     */
    private $date_fin;

    /**
     * @var bool
     *
     * @ORM\Column(name="archivage", type="boolean", nullable=true)
     */
    private $archivage;

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

    public function getArchiveDir(){
        $dir = "uploads/archives/" . $this->getType()->getRoute();

        return $dir;
    }

    public function getUploadDir()
    {
        $dir = "uploads/resources/" . $this->getType()->getRoute();

        return $dir;
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

    /**
     * Set type
     *
     * @param \Orange\HomeBundle\Entity\Type $type
     * @return Fichier
     */
    public function setType(\Orange\HomeBundle\Entity\Type $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Orange\HomeBundle\Entity\Type 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set extension
     *
     * @param \Orange\HomeBundle\Entity\Extension $extension
     * @return Fichier
     */
    public function setExtension(\Orange\HomeBundle\Entity\Extension $extension = null)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return \Orange\HomeBundle\Entity\Extension 
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set date_fin
     *
     * @param \DateTime $dateFin
     * @return Fichier
     */
    public function setDateFin($dateFin)
    {
        $this->date_fin = $dateFin;

        return $this;
    }

    /**
     * Get date_fin
     *
     * @return \DateTime 
     */
    public function getDateFin()
    {
        return $this->date_fin;
    }

    /**
     * Set archivage
     *
     * @param boolean $archivage
     * @return Fichier
     */
    public function setArchivage($archivage)
    {
        $this->archivage = $archivage;

        return $this;
    }

    /**
     * Get archivage
     *
     * @return boolean 
     */
    public function getArchivage()
    {
        return $this->archivage;
    }
}
