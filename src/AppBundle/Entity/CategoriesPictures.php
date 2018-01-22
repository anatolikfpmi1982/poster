<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CategoriesPictures
 *
 * @ORM\Table(name="categories_pictures")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoriesPicturesRepository")
 */
class CategoriesPictures
{
    /**
     * @var integer
     * @ORM\Column(name="weight", type="integer")
     */
    protected $weight;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="Category3", inversedBy="categoriesPictures")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $category;

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
     * @return CategoriesPictures
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
     * Set category
     *
     * @param Category3 $category
     *
     * @return CategoriesPictures
     */
    public function setCategory(Category3 $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return Category3
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set picture
     *
     * @param Picture $picture
     *
     * @return CategoriesPictures
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
