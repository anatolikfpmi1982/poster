<?php
namespace AppBundle\Entity;

use AppBundle\Entity\FrameColor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Frame
 * @ORM\Table(name="categories")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 */
class Category3 implements ImageInterface
{
    /** class name */
    const JSON_NAME = 'categories3';

    /**
     * Image sub folder
     */
    const IMAGE_PATH = 'categories3';

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="title", type="string", length=300)
     *
     * @Assert\Length(min=1, max=100)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

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
     * @var boolean
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     */
    private $isActive;

    /**
     * @var ArrayCollection
     * Many Categories have Many Pictures.
     * @ORM\ManyToMany(targetEntity="\AppBundle\Entity\Picture", mappedBy="categories")
     */
    private $pictures;

    /**
     * Many Categories have One Category.
     * @ORM\ManyToOne(targetEntity="Category3", inversedBy="children")
     * @ORM\JoinColumn(name="parent_category", referencedColumnName="id")
     */
    private $parent_category;

    /**
     * One Category has Many Categories.
     * @ORM\OneToMany(targetEntity="Category3", mappedBy="parent_category")
     */
    private $children;

    /**
     * @var string
     * @ORM\Column(name="slug", type="string", length=300)
     *
     * @Assert\Length(min=1, max=300)
     */
    private $slug;

    /**
     * Feature constructor.
     */
    public function __construct()
    {
        $this->pictures = new ArrayCollection();
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return (string)$this->title;
    }

    /**
     * Set createdAt
     *
     * @param  \DateTime $createdAt
     * @return Frame
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
     * @return Frame
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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Frame
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Frame
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param boolean $isActive
     * @return Frame
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
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

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Frame
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set parentCategory
     *
     * @param \AppBundle\Entity\Category3 $parentCategory
     *
     * @return Category3
     */
    public function setParentCategory(\AppBundle\Entity\Category3 $parentCategory = null)
    {
        $this->parent_category = $parentCategory;

        return $this;
    }

    /**
     * Get parentCategory
     *
     * @return \AppBundle\Entity\Category3
     */
    public function getParentCategory()
    {
        return $this->parent_category;
    }

    /**
     * Add child
     *
     * @param \AppBundle\Entity\Category3 $child
     *
     * @return Category3
     */
    public function addChild(\AppBundle\Entity\Category3 $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \AppBundle\Entity\Category3 $child
     */
    public function removeChild(\AppBundle\Entity\Category3 $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Category3
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
}
