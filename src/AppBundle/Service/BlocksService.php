<?php
namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BlocksService
 */
class BlocksService {
    const MODULE_CATEGORY_ID = 2;

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
     * @return array
     */
    public function getCategoriesBlock() {
        $categories = $this->em->getRepository( 'AppBundle:Category3' )->getCatalogMenu();
        if($categories) {
            $module = [];
            foreach($categories as $key => $parent) {
                if($parent['id'] == self::MODULE_CATEGORY_ID) {
                    $parent['identifier'] = 'm' . $parent['id'];
                    $parent['title'] = 'Модульные картины';
                    $parent['module'] = true;
                    if(!empty($parent['children'])) {
                        foreach($parent['children'] as $key2 => $parent2) {
                            $parent['children'][$key2]['identifier'] = 'm' . $parent2['id'];
                            if(!empty($parent2['children'])) {
                                foreach($parent2['children'] as $key3 => $parent3) {
                                    $parent['children'][$key2]['children'][$key3]['identifier'] = 'm' . $parent3['id'];
                                    if(!empty($parent3['children'])) {
                                        foreach($parent3['children'] as $key4 => $parent4) {
                                            $parent['children'][$key2]['children'][$key3]['children'][$key4]['identifier'] = 'm' . $parent4['id'];
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $module = $parent;
                }
            }
        }
        if(!empty($module)) {
            $categories[] = $module;
        }

        return $categories;
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