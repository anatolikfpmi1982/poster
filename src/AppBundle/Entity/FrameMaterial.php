<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Frame Material
 *
 * @ORM\Table(name="frame_materials")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FrameMaterialRepository")
 *
 * @JMS\ExclusionPolicy("all")
 */
class FrameMaterial implements ImageInterface
{
    /** class name */
    const JSON_NAME = 'frame_material';

    /**
     * Image sub folder
     */
    const IMAGE_PATH = 'frame_materials';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMS\Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100)
     *
     * @JMS\Expose
     */

    private $title;

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
     * @ORM\Column(name="price", type="float")
     *
     * @JMS\Expose
     */
    private $price;

    /**
     * @var integer
     *
     * @ORM\Column(name="min_area", type="float")
     *
     * @JMS\Expose
     */
    private $minArea;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_area", type="float")
     *
     * @JMS\Expose
     */
    private $maxArea;

    /**
     * @var float
     *
     * @ORM\Column(name="min_price", type="float")
     *
     * @JMS\Expose
     */
    private $minPrice;

    /**
     * @var float
     *
     * @ORM\Column(name="max_price", type="float")
     *
     * @JMS\Expose
     */
    private $maxPrice;

    /**
     * @var Image
     * @ORM\OneToOne(targetEntity="Image", fetch="EXTRA_LAZY", cascade={"persist"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $image;

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


    public function __toString()
    {
        return (string)$this->title;
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
     * @return FrameMaterial
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
     * Set createdAt
     *
     * @param  \DateTime $createdAt
     * @return FrameMaterial
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
     * @return FrameMaterial
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
     * @return FrameMaterial
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Set ratio
     *
     * @param float $ratio
     *
     * @return FrameMaterial
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
     * Set minArea
     *
     * @param float $minArea
     *
     * @return FrameMaterial
     */
    public function setMinArea($minArea)
    {
        $this->minArea = $minArea;

        return $this;
    }

    /**
     * Get minArea
     *
     * @return float
     */
    public function getMinArea()
    {
        return $this->minArea;
    }

    /**
     * Set maxArea
     *
     * @param float $maxArea
     *
     * @return FrameMaterial
     */
    public function setMaxArea($maxArea)
    {
        $this->maxArea = $maxArea;

        return $this;
    }

    /**
     * Get maxArea
     *
     * @return float
     */
    public function getMaxArea()
    {
        return $this->maxArea;
    }

    /**
     * Set minPrice
     *
     * @param float $minPrice
     *
     * @return FrameMaterial
     */
    public function setMinPrice($minPrice)
    {
        $this->minPrice = $minPrice;

        return $this;
    }

    /**
     * Get minPrice
     *
     * @return float
     */
    public function getMinPrice()
    {
        return $this->minPrice;
    }

    /**
     * Set maxPrice
     *
     * @param float $maxPrice
     *
     * @return FrameMaterial
     */
    public function setMaxPrice($maxPrice)
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }

    /**
     * Get maxPrice
     *
     * @return float
     */
    public function getMaxPrice()
    {
        return $this->maxPrice;
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
     * Set image
     *
     * @param Image $image
     *
     * @return FrameMaterial
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
     * @JMS\VirtualProperty
     * @JMS\SerializedName("image_link")
     */
    public function getImageLink()
    {
        return '/files/' . $this->image->getEntityName() . '/' . $this->image->getFilename();
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return FrameMaterial
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
}
