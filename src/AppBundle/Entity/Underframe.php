<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;

/**
 * Underframe
 * @ORM\Table(name="underframes")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UnderframeRepository")
 *
 * @JMS\ExclusionPolicy("all")
 */
class Underframe implements ImageInterface
{
    /** class name */
    const JSON_NAME = 'underframe';

    /**
     * Image sub folder
     */
    const IMAGE_PATH = 'underframes';

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
     * @var float
     * @ORM\Column(name="depth", type="float")
     *
     * @JMS\Expose
     */
    private $depth;

    /**
     * @var float
     *
     * @ORM\Column(name="ratio", type="float")
     * @JMS\Expose
     */
    private $ratio;

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
     * @var Image
     * @ORM\OneToOne(targetEntity="Image", fetch="EAGER", cascade={"persist"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     *
     */
    private $image;

    /**
     * @var boolean
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     */
    private $isActive;

    public function __toString()
    {
        return (string)$this->id;
    }

    /**
     * Set createdAt
     *
     * @param  \DateTime $createdAt
     * @return UnderFrame
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
     * @return UnderFrame
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
     * @return boolean
     */
    public function isIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param boolean $isActive
     * @return Underframe
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

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
     * Set depth
     *
     * @param float $depth
     *
     * @return Underframe
     */
    public function setDepth($depth)
    {
        $this->depth = $depth;

        return $this;
    }

    /**
     * Get depth
     *
     * @return float
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * Set ratio
     *
     * @param float $ratio
     *
     * @return Underframe
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
     * Set image
     *
     * @param Image $image
     *
     * @return Underframe
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
