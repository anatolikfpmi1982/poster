<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Author
 *
 * @ORM\Table(name="parser_log")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ParserLogRepository")
 */
class ParserLog
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
     * @var string
     *
     * @ORM\Column(name="message", type="string", nullable=true)
     */
    private $message;

    /**
     * @var \DateTime
     * @ORM\Column(name="file_date", type="datetime", nullable=true)
     */
    private $fileDate;
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
        return (string)$this->message;
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
     * Set updatedAt
     *
     * @param  \DateTime $updatedAt
     * @return ParserLog
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
     * Set message
     *
     * @param string $message
     *
     * @return ParserLog
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set fileDate
     *
     * @param \DateTime $fileDate
     *
     * @return ParserLog
     */
    public function setFileDate($fileDate)
    {
        $this->fileDate = $fileDate;

        return $this;
    }

    /**
     * Get fileDate
     *
     * @return \DateTime
     */
    public function getFileDate()
    {
        return $this->fileDate;
    }
}
