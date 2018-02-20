<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AuthorsPictures
 *
 * @ORM\Table(name="authors_pictures")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AuthorsPicturesRepository")
 */
class AuthorsPictures
{
    /**
     * @var integer
     * @ORM\Column(name="weight", type="integer")
     */
    protected $weight;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="Author", inversedBy="authorsPictures")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $author;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="Picture", inversedBy="categoriesPictures")
     * @ORM\JoinColumn(name="picture_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $picture;

    /**
     * Return string entity name from admin.
     *
     * @return string
     */
    public function __toString(){
        return $this->category->getId() . '-' . $this->picture->getId();
    }

    /**
     * Set weight
     *
     * @param integer $weight
     *
     * @return AuthorsPictures
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return integer
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set author
     *
     * @param Author $author
     *
     * @return AuthorsPictures
     */
    public function setAuthor(Author $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set picture
     *
     * @param Picture $picture
     *
     * @return AuthorsPictures
     */
    public function setPicture(Picture $picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return Picture
     */
    public function getPicture()
    {
        return $this->picture;
    }
}
