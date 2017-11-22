<?php

/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

use Sonata\ClassificationBundle\Entity\BaseCategory as BaseCategory;
use AppBundle\Entity\Picture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Category
 *
 * @ORM\Table(name="classification__category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 *
 * @Serializer\ExclusionPolicy("all")
 * @Serializer\XmlRoot("_category")
 */
class Category extends BaseCategory
{
    /**
     * @var int $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Expose("true")
     * @Serializer\Since("1.0")
     * @Serializer\SerializedName("id")
     * @Serializer\Groups({"sonata_api_read", "sonata_api_write", "sonata_search"})
     */
    protected $id;

    /**
     * @var ArrayCollection
     * Many Categories have Many Pictures.
     * @ORM\ManyToMany(targetEntity="\AppBundle\Entity\Picture", mappedBy="categories")
     */
    private $pictures;

    public function __construct() {
        $this->pictures = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param ArrayCollection $pictures
     * @return Category
     */
    public function setPictures($pictures)
    {
        $this->pictures = $pictures;

        return $this;
    }

    /**
     * Add picture
     *
     * @param \AppBundle\Entity\Picture $picture
     *
     * @return Category
     */
    public function addPicture(Picture $picture)
    {
        $this->pictures[] = $picture;

        return $this;
    }

    /**
     * Remove picture
     *
     * @param \AppBundle\Entity\Picture $picture
     */
    public function removePicture(Picture $picture)
    {
        $this->pictures->removeElement($picture);
    }

    /**
     * Get pictures
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getPictures()
    {
        return $this->pictures;
    }
}
