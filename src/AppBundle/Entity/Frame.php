<?php
namespace AppBundle\Entity;

use AppBundle\Entity\FrameColor;
use AppBundle\Entity\FrameMaterial;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Frame
 * @ORM\Table(name="frames")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FrameRepository")
 *
 * @JMS\ExclusionPolicy("all")
 */
class Frame implements ImageInterface
{
    /** class name */
    const JSON_NAME = 'frame';

    /**
     * Image sub folder
     */
    const IMAGE_PATH = 'frames';

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMS\Expose
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="title", type="string", length=300)
     *
     * @Assert\Length(min=1, max=100)
     *
     * @JMS\Expose
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var float
     * @ORM\Column(name="height", type="float")
     *
     * @JMS\Expose
     */
    private $height;

    /**
     * @var float
     * @ORM\Column(name="width", type="float")
     *
     * @JMS\Expose
     */
    private $width;

    /**
     * @var float
     *
     * @ORM\Column(name="ratio", type="float")
     *
     * @JMS\Expose
     */
    private $ratio;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float", nullable=true)
     *
     * @JMS\Expose
     */
    private $price;

    /**
     * @var boolean
     * @ORM\Column(name="use_ratio", type="boolean")
     *
     * @JMS\Expose
     */
    private $useRatio;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="\AppBundle\Entity\Image", fetch="EXTRA_LAZY", cascade={"persist"})
     * @ORM\JoinTable(name="frames_images",
     *      joinColumns={
     *          @ORM\JoinColumn(name="frame_id", referencedColumnName="id"),
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="image_id", referencedColumnName="id", onDelete="CASCADE")
     *      }
     * )
     * @JMS\Groups({"images"})
     */
    private $images;

    /**
     * @var FrameColor
     * @ORM\ManyToOne(targetEntity="FrameColor")
     * @ORM\JoinColumn(name="color_id", referencedColumnName="id")
     */
    private $color;

    /**
     * @var FrameMaterial
     * @ORM\ManyToOne(targetEntity="FrameMaterial")
     * @ORM\JoinColumn(name="material_id", referencedColumnName="id")
     */
    private $material;

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
     * Feature constructor.
     */
    public function __construct()
    {
        $this->images = new ArrayCollection();
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
     * @param ArrayCollection $images
     * @return Frame
     */
    public function setImages($images)
    {
        $this->images = $images;

        return $this;
    }

    /**
     * @param Image $image
     * @return Frame
     */
    public function addImage(Image $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * @param Image $image
     * @return Frame
     */
    public function removeImage(Image $image)
    {
        $this->images->removeElement($image);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     * @return Frame
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     * @return Frame
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
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
     * @return FrameColor
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param FrameColor $color
     * @return Frame
     */
    public function setColor(FrameColor $color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return FrameMaterial
     */
    public function getMaterial()
    {
        return $this->material;
    }

    /**
     * @param FrameMaterial $material
     * @return Frame
     */
    public function setMaterial(FrameMaterial $material)
    {
        $this->material = $material;

        return $this;
    }

    /**
     * Set ratio
     *
     * @param float $ratio
     *
     * @return Frame
     */
    public function setRatio($ratio)
    {
        $this->ratio = $ratio;

        return $this;
    }

    /**
     * Get ratio
     *
     * @return float
     */
    public function getRatio()
    {
        return $this->ratio;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Frame
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set useRatio
     *
     * @param boolean $useRatio
     *
     * @return Frame
     */
    public function setUseRatio($useRatio)
    {
        $this->useRatio = $useRatio;

        return $this;
    }

    /**
     * Get useRatio
     *
     * @return boolean
     */
    public function getUseRatio()
    {
        return $this->useRatio;
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
     * @JMS\VirtualProperty
     * @JMS\SerializedName("image_link")
     */
    public function getImageLink()
    {
        if(count($this->images) > 0) {
            return '/files/' . $this->images[0]->getEntityName() . '/mini_thumb/' . $this->images[0]->getFilename();
        }

    }
}
