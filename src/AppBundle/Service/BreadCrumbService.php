<?php
namespace AppBundle\Service;

use AppBundle\Entity\Category3;
use AppBundle\Entity\Page;
use AppBundle\Entity\Picture;
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
            case 'static_page':
                $result = $this->buildStaticPageBreadCrumb($param);
                break;
            case 'picture':
                $result = $this->buildPictureBreadCrumb($param);
                break;
        }
        return $result;
    }

    /**
     * Get full static page bread crumb path.
     *
     * @param string $pageSlug
     *
     * @return array
     */
    public function buildStaticPageBreadCrumb( $pageSlug ) {
        /** @var Page $page */
        $page = $this->em->getRepository( 'AppBundle:Page' )->findOneBy( [ 'slug' => $pageSlug, 'isActive' => true ] );
        return [[
            'title' => $page->getTitle(),
            'url' => $this->container->get('router')->generate('page', array('slug' => $page->getSlug())),
        ]];
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
     * Get full picture bread crumb path.
     *
     * @param int $pictureId
     *
     * @return array
     */
    public function buildPictureBreadCrumb( $pictureId ) {
        $picture = $this->em->getRepository( 'AppBundle:Picture' )->find( $pictureId );
        $result = [];
        if($picture instanceof Picture) {
            $categories = $picture->getCategories();
            $result[0] = [
                'title' => $picture->getTitle(),
                'url' => $this->container->get('router')->generate('picture', array('id' => $picture->getId())),
            ];

            if(count($categories) > 0) {
                /** @var Category3 $category */
                $category = $categories->first();
                $categoryBreadCrumbs = $this->buildCategoryBreadCrumb($category->getSlug());
                foreach($categoryBreadCrumbs as $categoryBreadCrumb) {
                    $result[] = $categoryBreadCrumb;
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