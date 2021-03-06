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
     * @ORM\Column(name="title", type="string", length=100, nullable=true, options={"default" : ""})
     */
    private $title = '';

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="string", length=5000, nullable=true)
     */
    private $body = '';

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note = '';

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", nullable=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="file_name", type="string", nullable=true)
     */
    private $name;

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
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $image;

    /**
     * @var Author
     * @ORM\ManyToOne(targetEntity="Author")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $author;

    /**
     * @var Popular
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Popular", inversedBy="pictures")
     * @ORM\JoinColumn(name="popular_id", referencedColumnName="id", onDelete="SET NULL")
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
     * @var PictureForm
     * @ORM\ManyToOne(targetEntity="PictureForm")
     * @ORM\JoinColumn(name="form_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $form;

    /**
     * @var integer
     *
     * @ORM\Column(name="popularity", type="integer", nullable=true, options={"default" : 0})
     */
    private $popularity = 0;

    /**
     * @var CategoriesPictures
     *
     * @ORM\OneToMany(targetEntity="CategoriesPictures", mappedBy="picture", cascade={"persist"})
     */
    protected $categoriesPictures;

    /**
     * @var AuthorsPictures
     *
     * @ORM\OneToMany(targetEntity="AuthorsPictures", mappedBy="picture", cascade={"persist"})
     */
    protected $authorsPictures;

    /**
     * Picture constructor.
     */
    public function __construct() {
        $this->categories = new ArrayCollection();
        $this->similar = new ArrayCollection();
        $this->colors = new ArrayCollection();
        $this->categoriesPictures = new ArrayCollection();
        $this->authorsPictures = new ArrayCollection();
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
     * Generate code
     * @return Picture
     */
    public function generateCode()
    {
        $this->code = 100500 + $this->id;

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
     * @param Category3 $category
     * @return Picture
     */
    public function addCategory(Category3 $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * @param Category3 $category
     * @return Picture
     */
    public function removeCategory(Category3 $category)
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

    /**
     * Set form
     *
     * @param PictureForm $form
     *
     * @return Picture
     */
    public function setForm(PictureForm $form = null)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Get form
     *
     * @return PictureForm
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Picture
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set note
     *
     * @param string $note
     *
     * @return Picture
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @return int
     */
    public function getPopularity() {
        return $this->popularity;
    }

    /**
     * @param int $popularity
     *
     * @return Picture
     */
    public function setPopularity( $popularity ) {
        $this->popularity = $popularity;

        return $this;
    }

    /**
     * Add categoriesPicture
     *
     * @param CategoriesPictures $categoriesPicture
     *
     * @return Picture
     */
    public function addCategoriesPicture(CategoriesPictures $categoriesPicture)
    {
        $this->categoriesPictures[] = $categoriesPicture;

        return $this;
    }

    /**
     * Remove categoriesPicture
     *
     * @param CategoriesPictures $categoriesPicture
     */
    public function removeCategoriesPicture(CategoriesPictures $categoriesPicture)
    {
        $this->categoriesPictures->removeElement($categoriesPicture);
    }

    /**
     * Get categoriesPictures
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategoriesPictures()
    {
        return $this->categoriesPictures;
    }

    /**
     * Add authorsPicture
     *
     * @param AuthorsPictures $authorsPicture
     *
     * @return Picture
     */
    public function addAuthorsPicture(AuthorsPictures $authorsPicture)
    {
        $this->authorsPictures[] = $authorsPicture;

        return $this;
    }

    /**
     * Remove authorsPicture
     *
     * @param AuthorsPictures $authorsPicture
     */
    public function removeAuthorsPicture(AuthorsPictures $authorsPicture)
    {
        $this->authorsPictures->removeElement($authorsPicture);
    }

    /**
     * Get authorsPictures
     *
     * @return AuthorsPictures
     */
    public function getAuthorsPictures()
    {
        return $this->authorsPictures;
    }
}
