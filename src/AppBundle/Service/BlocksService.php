<?php
namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BlocksService
 */
class BlocksService {
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
     *
     * @param EntityManager $em
     * @param ContainerInterface $container
     */
    public function __construct( EntityManager $em, ContainerInterface $container ) {
        $this->em        = $em;
        $this->container = $container;
    }

    /**
     * Return data for popular sidebar.
     *
     * @return array
     */
    public function getPopularBlock() {
        return $this->em->getRepository( 'AppBundle:Review' )->getLatestReviews();
    }

    /**
     * Return data for main menu.
     *
     * @return \AppBundle\Entity\MainMenu[]|array
     */
    public function getMainMenuBlock() {
        return $this->em->getRepository( 'AppBundle:MainMenu' )->findBy( [ 'isActive' => true ], [ 'weight' => 'ASC' ] );
    }

    /**
     * Return data for category sidebar.
     *
     * @param array $settings
     *
     * @return array
     */
    public function getCategoriesBlock($settings) {
        return $this->em->getRepository( 'AppBundle:Category3' )->getCatalogMenu($settings);
    }

    /**
     * @return array
     */
    public function getLastVisitedItems() {
        return $this->container->get( 'session' )->get( 'lastVisited' );
    }

    /**
     * @return array
     */
    public function getDeferredItems() {
        return $this->container->get( 'session' )->get( 'deferred' );
    }


    /**
     * Return data for slider main page.
     *
     * @return array
     */
    public function getSliderItems() {
        return $this->em->getRepository( 'AppBundle:SliderItem' )->findBy( [ 'isActive' => true ], [ 'weight' => 'ASC' ] );
    }

    /**
     * Return data for similar block.
     *
     * @return array
     */
    public function getSimilarBlock($id) {
        return $this->em->getRepository( 'AppBundle:Picture' )->getActiveSimilar($id);
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