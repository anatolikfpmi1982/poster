<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Page
 *
 * @ORM\Table(name="pictures")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PictureRepository")
 */
class Picture implements ImageInterface
{
    /** class name */
    const JSON_NAME = 'picture';

    /**
     * Image sub folder
     */
    const IMAGE_PATH = 'pictures';

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
     * @ORM\Column(name="title", type="string", length=100)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string")
     */
    private $slug;

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
     * @var boolean
     * @ORM\Column(name="is_top", type="boolean", nullable=false)
     */
    private $isTop;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=30, nullable=false)
     */
    private $code;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var float
     *
     * @ORM\Column(name="ratio", type="float")
     */
    private $ratio;

    /**
     * @var boolean
     *
     * @ORM\Column(name="type", type="boolean")
     */
    private $type;

    /**
     * Many Pictures have Many Categories.
     *
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Category3", inversedBy="pictures", fetch="EXTRA_LAZY", cascade={"persist"})
     * @ORM\JoinTable(name="pictures3_categories")
     */
    private $categories;

    /**
     * @var Image
     * @ORM\OneToOne(targetEntity="Image", fetch="EXTRA_LAZY", cascade={"persist"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     */
    private $image;

    /**
     * @var Image
     * @ORM\OneToOne(targetEntity="Image", fetch="EXTRA_LAZY", cascade={"persist"})
     * @ORM\JoinColumn(name="image_banner_id", referencedColumnName="id")
     */
    private $imageBanner;

    /**
     * @var Image
     * @ORM\OneToOne(targetEntity="Image", fetch="EXTRA_LAZY", cascade={"persist"})
     * @ORM\JoinColumn(name="image_module_id", referencedColumnName="id")
     */
    private $imageModule;

    /**
     * @var Image
     * @ORM\OneToOne(targetEntity="Image", fetch="EXTRA_LAZY", cascade={"persist"})
     * @ORM\JoinColumn(name="image_frame_id", referencedColumnName="id")
     */
    private $imageFrame;

    /**
     * @var Author
     * @ORM\ManyToOne(targetEntity="Author")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    private $author;

    /**
     * @var PictureColor
     * @ORM\ManyToOne(targetEntity="PictureColor")
     * @ORM\JoinColumn(name="picture_color_id", referencedColumnName="id")
     */
    private $color;

    /**
     * @var Popular
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Popular")
     * @ORM\JoinColumn(name="popular_id", referencedColumnName="id")
     */
    private $popular;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="\AppBundle\Entity\Picture", fetch="EXTRA_LAZY", cascade={"persist"})
     * @ORM\JoinTable(name="similar_pictures",
     *      joinColumns={
     *          @ORM\JoinColumn(name="picture_id", referencedColumnName="id"),
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="similar_id", referencedColumnName="id", onDelete="CASCADE")
     *      }
     * )
     */
    private $similar;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="\AppBundle\Entity\PictureColor", fetch="EXTRA_LAZY", cascade={"persist"})
     * @ORM\JoinTable(name="pictures_colors",
     *      joinColumns={
     *          @ORM\JoinColumn(name="picture_id", referencedColumnName="id"),
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="color_id", referencedColumnName="id", onDelete="CASCADE")
     *      }
     * )
     */
    private $colors;

    /**
     * Picture constructor.
     */
    public function __construct() {
        $this->categories = new ArrayCollection();
        $this->similar = new ArrayCollection();
        $this->colors = new ArrayCollection();
    }

    /**
     * Return string entity name from admin.
     *
     * @return string
     */
    public function __toString(){
        return $this->title . '( #' . $this->id .' )';
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
     * Set title
     *
     * @param string $title
     *
     * @return Picture
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Picture
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return Picture
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Picture
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param boolean $type
     * @return Picture
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return Picture
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return float
     */
    public function getRatio()
    {
        return $this->ratio;
    }

    /**
     * @param float $ratio
     * @return Picture
     */
    public function setRatio($ratio)
    {
        $this->ratio = $ratio;

        return $this;
    }

    /**
     * Set createdAt
     *
     * @param  \DateTime $createdAt
     * @return Picture
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
     * @return Picture
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
     * @return boolean
     */
    public function isIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param boolean $isActive
     * @return Picture
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @param ArrayCollection $categories
     * @return Picture
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @param Category $category
     * @return Picture
     */
    public function addCategory(Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * @param Category $category
     * @return Picture
     */
    public function removeCategory(Category $category)
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @return Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param Author $author
     * @return Picture
     */
    public function setAuthor(Author $author)
    {
        $this->author = $author;

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
     * @return Popular
     */
    public function getPopular() {
        return $this->popular;
    }

    /**
     * @param Popular|null $popular
     *
     * @return Picture
     */
    public function setPopular( $popular ) {
        $this->popular = $popular;

        return $this;
    }



    /**
     * Set imageBanner
     *
     * @param Image $imageBanner
     *
     * @return Picture
     */
    public function setImageBanner(Image $imageBanner = null)
    {
        $this->imageBanner = $imageBanner;

        return $this;
    }

    /**
     * Get imageBanner
     *
     * @return Image
     */
    public function getImageBanner()
    {
        return $this->imageBanner;
    }

    /**
     * Set imageModule
     *
     * @param Image $imageModule
     *
     * @return Picture
     */
    public function setImageModule(Image $imageModule = null)
    {
        $this->imageModule = $imageModule;

        return $this;
    }

    /**
     * Get imageModule
     *
     * @return Image
     */
    public function getImageModule()
    {
        return $this->imageModule;
    }

    /**
     * Set imageFrame
     *
     * @param Image $imageFrame
     *
     * @return Picture
     */
    public function setImageFrame(Image $imageFrame = null)
    {
        $this->imageFrame = $imageFrame;

        return $this;
    }

    /**
     * Get imageFrame
     *
     * @return Image
     */
    public function getImageFrame()
    {
        return $this->imageFrame;
    }

    /**
     * Set image
     *
     * @param Image $image
     *
     * @return Picture
     */
    public function setImage(Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add similar
     *
     * @param Picture $similar
     *
     * @return Picture
     */
    public function addSimilar(Picture $similar)
    {
        $this->similar[] = $similar;

        return $this;
    }

    /**
     * Remove similar
     *
     * @param Picture $similar
     */
    public function removeSimilar(Picture $similar)
    {
        $this->similar->removeElement($similar);
    }

    /**
     * Get similar
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSimilar()
    {
        return $this->similar;
    }

    /**
     * Set isTop
     *
     * @param boolean $isTop
     *
     * @return Picture
     */
    public function setIsTop($isTop)
    {
        $this->isTop = $isTop;

        return $this;
    }

    /**
     * Get isTop
     *
     * @return boolean
     */
    public function getIsTop()
    {
        return $this->isTop;
    }

    /**
     * Set color
     *
     * @param PictureColor $color
     *
     * @return Picture
     */
    public function setColor(PictureColor $color = null)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return PictureColor
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Add color
     *
     * @param PictureColor $color
     *
     * @return Picture
     */
    public function addColor(PictureColor $color)
    {
        $this->colors[] = $color;

        return $this;
    }

    /**
     * Remove color
     *
     * @param PictureColor $color
     */
    public function removeColor(PictureColor $color)
    {
        $this->colors->removeElement($color);
    }

    /**
     * Get colors
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getColors()
    {
        return $this->colors;
    }
}
