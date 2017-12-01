<?php
namespace AppBundle\Service;

use AppBundle\Entity\Category3;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BreadCrumbService
 */
class BreadCrumbService {

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * BreadCrumbService constructor.
     *
     * @param EntityManager $em
     * @param ContainerInterface $container
     */
    public function __construct( EntityManager $em, ContainerInterface $container ) {
        $this->em = $em;
        $this->container = $container;
    }

    /**
     * Build bread crumb.
     *
     * @param string|int $param
     * @param string $type category|picture|
     *
     * @return array
     */
    public function buildBreadCrumb($param, $type) {
        $result = [];
        switch($type) {
            case 'category':
                $result = $this->buildCategoryBreadCrumb($param);
                break;
        }
        return $result;
    }

    /**
     * Get full category bread crumb path.
     *
     * @param string $categorySlug
     *
     * @return array
     */
    public function buildCategoryBreadCrumb( $categorySlug ) {
        $category = $this->em->getRepository( 'AppBundle:Category3' )->findOneBySlug( $categorySlug );
        $result = [];
        if($category instanceof Category3) {
            $result[0] = $this->getBreadCrumbCategory($category);
            $parent = $category->getParentCategory();
            if($parent instanceof Category3) {
                $result[1] = $this->getBreadCrumbCategory($parent);
                $parentParent = $parent->getParentCategory();
                if($parentParent instanceof Category3) {
                    $result[2] = $this->getBreadCrumbCategory($parentParent);
                }
            }
        }

        return $result;
    }

    /**
     * Get category part for bread crumb.
     *
     * @param Category3 $category
     *
     * @return array
     */
    protected function getBreadCrumbCategory(Category3 $category) {
        return [
            'title' => $category->getTitle(),
            'url' => $this->container->get('router')->generate('category', array('slug' => $category->getSlug())),
        ];
    }
}