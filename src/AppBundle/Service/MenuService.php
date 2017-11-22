<?php
namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;

class MenuService
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

    public function getMainMenu()
    {
        return  $this->em->getRepository('AppBundle:MainMenu')->findBy(['isActive' => true]);
    }
}