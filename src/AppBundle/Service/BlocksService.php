<?php
namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BlocksService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * BlocksService constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function getPopularBlock() {
        return $this->em->getRepository( 'AppBundle:Review' )->getLatestReviews();
    }

    public function getMainMenuBlock() {
        return $this->em->getRepository('AppBundle:MainMenu')->findBy([], ['weight' => 'ASC']);
    }

    /**
    * @return array
    */
    public function getCategoriesBlock()
    {
        $categories = $this->em->getRepository('AppBundle:Category3')->getRoot();

        return $categories;
    }

    /**
     * @return array
     */
    public function getLastVisitedItems()
    {
        return $this->container->get('session')->get('lastVisited');
    }

    /**
     * @return array
     */
    public function getSliderItems()
    {
        return $this->em->getRepository('AppBundle:SliderItems')->findBy([], ['weight' => 'ASC']);
    }

//    /**
//     * @return array
//     */
//    public function getCategoriesBlock()
//    {
//        $categoryManager = $this->container->get('sonata.classification.manager.category');
//        $currentContext = false;
//
//        // all root categories.
//        $rootCategoriesSplitByContexts = $categoryManager->getRootCategoriesSplitByContexts(false);
//
//        $currentCategories = current($rootCategoriesSplitByContexts)[0]->getChildren();
//
//        return $currentCategories;
//    }

//    private function generateTree($cats) {
//        return $cats;
//    }
}