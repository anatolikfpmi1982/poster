<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;

/**
 * PictureForm
 *
 * @ORM\Table(name="picture_forms")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PictureFormRepository")
 *
 * @JMS\ExclusionPolicy("all")
 */
class PictureForm implements ImageInterface
{
    /** class name */
    const JSON_NAME = 'picture_form';

    /**
     * Image sub folder
     */
    const IMAGE_PATH = 'picture_forms';

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
     * @ORM\Column(name="name", type="string")
     *
     * @JMS\Expose
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="service_name", type="string", nullable=true)
     *
     * @JMS\Expose
     */
    private $serviceName;

    /**
     * @var Image
     * @ORM\OneToOne(targetEntity="Image", fetch="EXTRA_LAZY", cascade={"persist"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", onDelete="SET NULL")
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


    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->name;
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
     * @param string $name
     *
     * @return PictureForm
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set createdAt
     *
     * @param  \DateTime $createdAt
     * @return PictureForm
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
     * @return PictureForm
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
     * @return PictureForm
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Set serviceName
     *
     * @param string $serviceName
     *
     * @return PictureForm
     */
    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;

        return $this;
    }

    /**
     * Get serviceName
     *
     * @return string
     */
    public function getServiceName()
    {
        return $this->serviceName;
    }

    /**
     * Set ratio
     *
     * @param float $ratio
     *
     * @return PictureForm
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
     * @return PictureForm
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
}
