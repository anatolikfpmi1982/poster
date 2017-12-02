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
     * @var string
     *
     * @ORM\Column(name="seo_title", type="string", length=200)
     */
    private $seoTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="seo_description", type="text")
     */
    private $seoDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="seo_keywords", type="text")
     */
    private $seoKeywords;

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
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="\AppBundle\Entity\Tag2", fetch="EXTRA_LAZY", cascade={"persist"})
     * @ORM\JoinTable(name="categories_tags",
     *      joinColumns={
     *          @ORM\JoinColumn(name="category_id", referencedColumnName="id"),
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="tags_id", referencedColumnName="id", onDelete="CASCADE")
     *      }
     * )
     */
    private $tags;

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
        $this->children = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function __toString()
    {
        return (string)$this->title;
    }

    /**
     * Set createdAt
     *
     * @param  \DateTime $createdAt
     * @return Category3
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
     * @return Category3
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
     * @return Category3
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
     * @return Category3
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
     * @return Category3
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
    /**
     * @param ArrayCollection $pictures
     * @return Category3
     */
    public function setPictures($pictures)
    {
        $this->pictures = $pictures;

        return $this;
    }

    /**
     * Add picture
     *
     * @param Picture $picture
     *
     * @return Category3
     */
    public function addPicture(Picture $picture)
    {
        $this->pictures[] = $picture;

        return $this;
    }

    /**
     * Remove picture
     *
     * @param Picture $picture
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
     * @return Category3
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
     * @param Category3 $parentCategory
     *
     * @return Category3
     */
    public function setParentCategory(Category3 $parentCategory = null)
    {
        $this->parent_category = $parentCategory;

        return $this;
    }

    /**
     * Get parentCategory
     *
     * @return Category3
     */
    public function getParentCategory()
    {
        return $this->parent_category;
    }

    /**
     * Add child
     *
     * @param Category3 $child
     *
     * @return Category3
     */
    public function addChild(Category3 $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param Category3 $child
     */
    public function removeChild(Category3 $child)
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

    /**
     * Set seoTitle
     *
     * @param string $seoTitle
     *
     * @return Category3
     */
    public function setSeoTitle($seoTitle)
    {
        $this->seoTitle = $seoTitle;

        return $this;
    }

    /**
     * Get seoTitle
     *
     * @return string
     */
    public function getSeoTitle()
    {
        return $this->seoTitle;
    }

    /**
     * Set seoDescription
     *
     * @param string $seoDescription
     *
     * @return Category3
     */
    public function setSeoDescription($seoDescription)
    {
        $this->seoDescription = $seoDescription;

        return $this;
    }

    /**
     * Get seoDescription
     *
     * @return string
     */
    public function getSeoDescription()
    {
        return $this->seoDescription;
    }

    /**
     * Set seoKeywords
     *
     * @param string $seoKeywords
     *
     * @return Category3
     */
    public function setSeoKeywords($seoKeywords)
    {
        $this->seoKeywords = $seoKeywords;

        return $this;
    }

    /**
     * Get seoKeywords
     *
     * @return string
     */
    public function getSeoKeywords()
    {
        return $this->seoKeywords;
    }

    /**
     * Add tag
     *
     * @param Tag2 $tag
     *
     * @return Category3
     */
    public function addTag(Tag2 $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param Tag2 $tag
     */
    public function removeTag(Tag2 $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }
}
