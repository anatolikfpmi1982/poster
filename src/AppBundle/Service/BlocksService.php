<?php
namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;

class BlocksService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * BlocksService constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getPopularBlock() {
        return $this->em->getRepository( 'AppBundle:Review' )->getLatestReviews();
    }


//    public function getFrames()
//    {
//        return  $this->em->getRepository('AppBundle:Frame')->findBy(['isActive' => true]);
//    }
//
//    public function getSizes()
//    {
//        return  $this->em->getRepository('AppBundle:PictureSize')->findBy(['isActive' => true]);
//    }
}