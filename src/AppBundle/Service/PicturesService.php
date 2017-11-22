<?php
namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;

class PicturesService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * PicturesService constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getFrames()
    {
        return  $this->em->getRepository('AppBundle:Frame')->findBy(['isActive' => true]);
    }

    public function getSizes()
    {
        return  $this->em->getRepository('AppBundle:PictureSize')->findBy(['isActive' => true]);
    }
}