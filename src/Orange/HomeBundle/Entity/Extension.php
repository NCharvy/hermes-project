<?php

namespace Orange\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Extension
 *
 * @ORM\Table(name="dbdevco_extension")
 * @ORM\Entity(repositoryClass="Orange\HomeBundle\Repository\ExtensionRepository")
 */
class Extension
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
     * @ORM\ManyToOne(targetEntity="Orange\HomeBundle\Entity\Type")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=10, unique=true)
     */
    private $nom;


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
     * @return Extension
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
     * Set type
     *
     * @param \Orange\HomeBundle\Entity\Type $type
     * @return Extension
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
}
