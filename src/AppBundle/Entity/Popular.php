<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Popular
 *
 * @ORM\Table(name="popular")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PopularRepository")
 */
class Popular
{
    /** class name */
    const JSON_NAME = 'popular';

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
     * @ORM\Column(name="title", type="string", length=250)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=100)
     */
    private $slug;

    /**
     * One popular to several pictures.
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Picture", mappedBy="popular", cascade={"persist"})
     */
    private $pictures;

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;


    /**
     * Popular constructor.
     */
    public function __construct() {
//        $this->pictures = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->id;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return Popular
     */
    public function setTitle( $title ) {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return Popular
     */
    public function setSlug( $slug ) {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Set createdAt
     *
     * @param  \DateTime $createdAt
     * @return Mat
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param  \DateTime $updatedAt
     * @return Mat
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return Picture[]
     */
    public function getPictures() {
        return $this->pictures;
    }

    /**
     * @param mixed $pictures
     *
     * @return Popular
     */
    public function setPictures( $pictures ) {
        $this->pictures = $pictures;

        return $this;
    }

    /**
     * @param Picture $picture
     *
     * @return Popular
     */
    public function addPicture(Picture $picture) {
//        if(!$this->pictures->contains($picture) ) {
            $this->pictures[] = $picture;
//        }
        return $this;
    }

    /**
     * Remove sources
     *
     * @param Picture $picture
     *
     * @return Popular
     */
    public function removeSource(Picture $picture)
    {
        if($this->pictures->contains($picture) ) {
            $this->pictures->removeElement($picture);
        }

        return $this;
    }
}

