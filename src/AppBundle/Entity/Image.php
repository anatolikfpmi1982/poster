<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Image
 * @ORM\Table(name="images")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Image
{
    /** class name */
    const JSON_NAME = 'image';

    /** server path */
    const SERVER_PATH_TO_IMAGE_FOLDER = __DIR__.'/../../../web/files/';

    /**
     * Path to origin files
     */
    const SERVER_PATH_TO_IMAGE_ORIGIN_FOLDER = __DIR__.'/../../../web/files/origin/';

    /** thumb sub folder */
    const THUMB_IMAGE_FOLDER = 'thumb/';
    const THUMB_IMAGE_HEIGHT = 273;
    const THUMB_IMAGE_WIDTH = 365;

    /** thumb sub folder */
    const THUMB_MINI_IMAGE_FOLDER = 'mini_thumb/';
    const THUMB_MINI_IMAGE_HEIGHT = 143;
    const THUMB_MINI_IMAGE_WIDTH = 150;

    /** thumb sub folder */
    const THUMB_SMALL_IMAGE_FOLDER = 'small_thumb/';
    const THUMB_SMALL_IMAGE_HEIGHT = 38;
    const THUMB_SMALL_IMAGE_WIDTH = 54;

    /** thumb sub folder */
    const THUMB_MAX_IMAGE_FOLDER = 'max_thumb/';
    const THUMB_MAX_IMAGE_HEIGHT = 600;
    const THUMB_MAX_IMAGE_WIDTH = 800;

    const NORMAL_IMAGE_FOLDER = 'normal/';

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     */
    private $entityFolder;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=300, nullable=true)
     */
    private $name;

    /**
     * Unmapped property to handle file uploads
     */
    private $file;

    /**
     * @var string
     * @ORM\Column(name="filename", type="string", length=300, nullable=true)
     */
    private $filename;

    /**
     * @var string
     * @ORM\Column(name="path", type="string", length=500, nullable=true)
     */
    private $path;

    /**
     * @var string
     * @ORM\Column(name="entity_name", type="string", length=50, nullable=true)
     */
    private $entityName;

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
     * @return string
     */
    public function __toString()
    {
        return '#'.(string)$this->id.' '.$this->filename;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Manages the copying of the file to the relevant place on the server
     */
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }
        if (!empty($this->getFilename())) {
            $this->onPreRemove();
        }
        $this->setFilename(sha1(uniqid((string)mt_rand(), true)).'.'.$this->getFile()->guessExtension());
        $this->setName(strtok($this->getFile()->getClientOriginalName(), '.'));

        // move takes the target directory and target filename as params
        $this->getFile()->move(
            $this->getOriginPath(),
            $this->getFilename()
        );
        $this->setPath($this->getOriginPath().$this->getFilename());


        // clean up the file property as you won't need it anymore
        $this->setFile(null);
    }

    /**
     * Lifecycle callback to upload the file to the server
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function lifecycleFileUpload()
    {
        $this->upload();
    }

    /**
     * Updates the hash value to force the preUpdate and postUpdate events to fire
     * @return $this
     */
    public function refreshUpdated()
    {
        $this->setUpdatedAt(new \DateTime());

        return $this;
    }

    /**
     * Set createdAt
     *
     * @param  \DateTime $createdAt
     * @return Image
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
     * @return Image
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
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
     * @return Image
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     * @return Image
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @ORM\PreRemove
     */
    public function onPreRemove()
    {
        $file = $this->getBaseFile();
        if (file_exists($file)) {
            unlink($file);
        }
        $file = $this->getThumbBaseFile();
        if (file_exists($file)) {
            unlink($file);
        }
        $file = $this->getMiniThumbBaseFile();
        if (file_exists($file)) {
            unlink($file);
        }

        $file = $this->getMaxThumbBaseFile();
        if (file_exists($file)) {
            unlink($file);
        }

        $file = $this->getSmallThumbBaseFile();
        if (file_exists($file)) {
            unlink($file);
        }

        $file = $this->getNormalBaseFile();
        if (file_exists($file)) {
            unlink($file);
        }
    }

    /**
     * Get img file
     * @return string
     */
    public function getBaseFile()
    {
        return $this->getBasePath().$this->filename;
    }

    /**
     * Get img file
     * @return string
     */
    public function getOriginFile()
    {
        return $this->getOriginPath().$this->filename;
    }

    /**
     * Get thumb img file
     * @return string
     */
    public function getThumbBaseFile()
    {
        return $this->getBasePath().self::THUMB_IMAGE_FOLDER.$this->filename;
    }

    /**
     * Get thumb img path
     * @return string
     */
    public function getThumbBasePath()
    {
        return $this->getBasePath().self::THUMB_IMAGE_FOLDER;
    }

    /**
     * Get thumb img file
     * @return string
     */
    public function getMiniThumbBaseFile()
    {
        return $this->getBasePath().self::THUMB_MINI_IMAGE_FOLDER.$this->filename;
    }

    /**
     * Get thumb img path
     * @return string
     */
    public function getMiniThumbBasePath()
    {
        return $this->getBasePath().self::THUMB_MINI_IMAGE_FOLDER;
    }

    /**
     * Get thumb img file
     * @return string
     */
    public function getSmallThumbBaseFile()
    {
        return $this->getBasePath().self::THUMB_SMALL_IMAGE_FOLDER.$this->filename;
    }

    /**
     * Get thumb img path
     * @return string
     */
    public function getSmallThumbBasePath()
    {
        return $this->getBasePath().self::THUMB_SMALL_IMAGE_FOLDER;
    }

    /**
     * Get thumb img file
     * @return string
     */
    public function getMaxThumbBaseFile()
    {
        return $this->getBasePath().self::THUMB_MAX_IMAGE_FOLDER.$this->filename;
    }

    /**
     * Get thumb img path
     * @return string
     */
    public function getMaxThumbBasePath()
    {
        return $this->getBasePath().self::THUMB_MAX_IMAGE_FOLDER;
    }

    /**
     * Get normal img path
     * @return string
     */
    public function getNormalBasePath()
    {
        return $this->getBasePath().self::NORMAL_IMAGE_FOLDER;
    }

    /**
     * Get normal img file
     * @return string
     */
    public function getNormalBaseFile()
    {
        return $this->getBasePath().self::NORMAL_IMAGE_FOLDER.$this->filename;
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return self::SERVER_PATH_TO_IMAGE_FOLDER.$this->getEntityFolder();
    }

    /**
     * @return string
     */
    public function getOriginPath()
    {
        return self::SERVER_PATH_TO_IMAGE_ORIGIN_FOLDER;
    }

    /**
     * @param string $entityName
     * @return $this
     */
    public function setEntityFolder($entityName)
    {
        $this->entityFolder = $entityName;

        return $this;
    }

    /**
     * @return $this
     */
    public function getEntityFolder()
    {
        $entity = $this->entityFolder ?: '';
        $entity = !$entity && !$this->entityName ? '' : $this->getEntityName();

        return $entity ? ($entity.'/') : '';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Image
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return Image
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * @param string $entityName
     * @return Image
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;

        return $this;
    }
}