<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Order
 *
 * @ORM\Table(name="orders")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderRepository")
 */
class Order
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="group_id", type="integer", nullable=true)
     */
    private $groupId = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="fullname", type="string")
     */
    private $fullname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string")
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string")
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string")
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="company", type="string", nullable=true)
     */
    private $company;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

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
    private $isActive = false;

    /**
     * @var boolean
     * @ORM\Column(name="is_done", type="boolean", nullable=false)
     */
    private $isDone = false;

    /**
     * @var integer
     *
     * @ORM\Column(name="height", type="integer", nullable=true)
     */
    private $height;

    /**
     * @var integer
     *
     * @ORM\Column(name="width", type="integer", nullable=true)
     */
    private $width;

    /**
     * @var integer
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var BannerMaterial
     * @ORM\ManyToOne(targetEntity="BannerMaterial")
     * @ORM\JoinColumn(name="banner_material_id", referencedColumnName="id")
     */
    private $bannerMaterial;

    /**
     * @var FrameMaterial
     * @ORM\ManyToOne(targetEntity="FrameMaterial")
     * @ORM\JoinColumn(name="frame_material_id", referencedColumnName="id")
     */
    private $frameMaterial;

    /**
     * @var Underframe
     * @ORM\ManyToOne(targetEntity="Underframe")
     * @ORM\JoinColumn(name="underframe_id", referencedColumnName="id")
     */
    private $underframe;

    /**
     * @var FrameColor
     * @ORM\ManyToOne(targetEntity="FrameColor")
     * @ORM\JoinColumn(name="frame_color_id", referencedColumnName="id")
     */
    private $frameColor;

    /**
     * @var Frame
     * @ORM\ManyToOne(targetEntity="Frame")
     * @ORM\JoinColumn(name="frame_id", referencedColumnName="id")
     */
    private $frame;

    /**
     * @var ModuleType
     * @ORM\ManyToOne(targetEntity="ModuleType")
     * @ORM\JoinColumn(name="module_type_id", referencedColumnName="id")
     */
    private $moduleType;

    /**
     * @var OwnPicture
     * @ORM\ManyToOne(targetEntity="OwnPicture")
     * @ORM\JoinColumn(name="own_picture_id", referencedColumnName="id")
     */
    private $ownPicture;

    /**
     * @var Picture
     * @ORM\ManyToOne(targetEntity="Picture")
     * @ORM\JoinColumn(name="picture_id", referencedColumnName="id")
     */
    private $picture;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", nullable=true)
     */
    private $type;

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
     * Set createdAt
     *
     * @param  \DateTime $createdAt
     * @return Order
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
     * @return Order
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
     * @return Order
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isDone
     *
     * @return boolean
     */
    public function getIsDone()
    {
        return $this->isDone;
    }

    /**
     * @return boolean
     */
    public function isIsDone()
    {
        return $this->isDone;
    }

    /**
     * @param boolean $isDone
     * @return Order
     */
    public function setIsDone($isDone)
    {
        $this->isDone = $isDone;

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
     * Set fullname
     *
     * @param string $fullname
     *
     * @return Order
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname
     *
     * @return string
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Order
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Order
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Order
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Order
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set company
     *
     * @param string $company
     *
     * @return Order
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Order
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set height
     *
     * @param integer $height
     *
     * @return Order
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set width
     *
     * @param integer $width
     *
     * @return Order
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return Order
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set bannerMaterial
     *
     * @param \AppBundle\Entity\BannerMaterial $bannerMaterial
     *
     * @return Order
     */
    public function setBannerMaterial(\AppBundle\Entity\BannerMaterial $bannerMaterial = null)
    {
        $this->bannerMaterial = $bannerMaterial;

        return $this;
    }

    /**
     * Get bannerMaterial
     *
     * @return \AppBundle\Entity\BannerMaterial
     */
    public function getBannerMaterial()
    {
        return $this->bannerMaterial;
    }

    /**
     * Set frameMaterial
     *
     * @param \AppBundle\Entity\FrameMaterial $frameMaterial
     *
     * @return Order
     */
    public function setFrameMaterial(\AppBundle\Entity\FrameMaterial $frameMaterial = null)
    {
        $this->frameMaterial = $frameMaterial;

        return $this;
    }

    /**
     * Get frameMaterial
     *
     * @return \AppBundle\Entity\FrameMaterial
     */
    public function getFrameMaterial()
    {
        return $this->frameMaterial;
    }

    /**
     * Set underframe
     *
     * @param \AppBundle\Entity\Underframe $underframe
     *
     * @return Order
     */
    public function setUnderframe(\AppBundle\Entity\Underframe $underframe = null)
    {
        $this->underframe = $underframe;

        return $this;
    }

    /**
     * Get underframe
     *
     * @return \AppBundle\Entity\Underframe
     */
    public function getUnderframe()
    {
        return $this->underframe;
    }

    /**
     * Set frameColor
     *
     * @param \AppBundle\Entity\FrameColor $frameColor
     *
     * @return Order
     */
    public function setFrameColor(\AppBundle\Entity\FrameColor $frameColor = null)
    {
        $this->frameColor = $frameColor;

        return $this;
    }

    /**
     * Get frameColor
     *
     * @return \AppBundle\Entity\FrameColor
     */
    public function getFrameColor()
    {
        return $this->frameColor;
    }

    /**
     * Set moduleType
     *
     * @param \AppBundle\Entity\ModuleType $moduleType
     *
     * @return Order
     */
    public function setModuleType(\AppBundle\Entity\ModuleType $moduleType = null)
    {
        $this->moduleType = $moduleType;

        return $this;
    }

    /**
     * Get moduleType
     *
     * @return \AppBundle\Entity\ModuleType
     */
    public function getModuleType()
    {
        return $this->moduleType;
    }

    /**
     * Set picture
     *
     * @param \AppBundle\Entity\Picture $picture
     *
     * @return Order
     */
    public function setPicture(\AppBundle\Entity\Picture $picture = null)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return \AppBundle\Entity\Picture
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Order
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
     * Set frame
     *
     * @param \AppBundle\Entity\Frame $frame
     *
     * @return Order
     */
    public function setFrame(\AppBundle\Entity\Frame $frame = null)
    {
        $this->frame = $frame;

        return $this;
    }

    /**
     * Get frame
     *
     * @return \AppBundle\Entity\Frame
     */
    public function getFrame()
    {
        return $this->frame;
    }

    /**
     * Set groupId
     *
     * @param integer $groupId
     *
     * @return Order
     */
    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;

        return $this;
    }

    /**
     * Get groupId
     *
     * @return integer
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * Set ownPicture
     *
     * @param \AppBundle\Entity\OwnPicture $ownPicture
     *
     * @return Order
     */
    public function setOwnPicture(\AppBundle\Entity\OwnPicture $ownPicture = null)
    {
        $this->ownPicture = $ownPicture;

        return $this;
    }

    /**
     * Get ownPicture
     *
     * @return \AppBundle\Entity\OwnPicture
     */
    public function getOwnPicture()
    {
        return $this->ownPicture;
    }
}
