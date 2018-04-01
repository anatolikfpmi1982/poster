<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Settings;
use AppBundle\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class FrontController
 */
class FrontController extends Controller {
    const FILE_SIZE_LIMIT = 10;

    /**
     * @var array
     */
    protected $data = [ ];

    /**
     * @var array
     */
    protected $blocks = [ 'CategoryMenu' => 1, 'ToDo' => 2, 'Reviews' => 3, 'MainMenu' => 4, 'BreadCrumb' => 5 ];

    /**
     * @var string
     */
    protected $menu = '';

    /**
     * @var string
     */
    protected $pageType = '';

    /**
     * @var string|int
     */
    protected $pageSlug;

    /**
     * @var string|int
     */
    protected $id;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var array
     */
    protected $settings = [ ];

    /**
     *
     */
    public function doBlocks() {
        $this->em = $this->get( 'doctrine.orm.entity_manager' );
        if ( $this->blocks && count( $this->blocks ) > 0 ) {
            asort( $this->blocks );
            foreach ( $this->blocks as $block => $order ) {
                $function = 'get' . $block . 'Block';
                if ( method_exists( $this, $function ) ) {
                    $this->data['site_blocks'][ $block ] = $this->$function();
                }
            }
        }

        $this->data['module_active'] = false;
        $this->data['active_menu']   = $this->menu;
        $this->data['site_settings'] = $this->getSiteSettings();
        $this->data['help_settings'] = $this->getHelpSettings();
        $this->data['header_cart']   = $this->getCart();
        $this->data['duplicate_category_id'] = CategoryRepository::MAIN_CATEGORY_ID;
    }

    /**
     * @return array
     */
    protected function getSiteSettings() {
        if ( ! $this->settings ) {
            $this->em      = $this->get( 'doctrine.orm.entity_manager' );
            $_siteSettings = $this->em->getRepository( 'AppBundle:Settings' )->findOneByName( 'site_settings' );
            $siteSettings  = [ ];
            if ( $_siteSettings instanceof Settings ) {
                $siteSettings                      = unserialize( $_siteSettings->getSettings() );
            }
        } else {
            $siteSettings = $this->settings;
        }

        return $siteSettings;
    }

    /**
     * @return array
     */
    protected function getCart() {
        $cart = $this->get( 'app.session_manager' )->getCart();
        if ( $cart ) {
            foreach ( $cart as $k => $v ) {
                if ( ! empty( $v['picture_id'] ) ) {
                    $cart[ $k ]['picture'] = $this->em->getRepository( 'AppBundle:Picture' )->findOneBy( [ 'isActive' => true, 'id' => $v['picture_id'] ] );
                } else {
                    $cart[ $k ]['own_picture'] = $this->em->getRepository( 'AppBundle:OwnPicture' )->findOneBy( [ 'id' => $v['own_picture_id'] ] );
                }
            }
        } else {
            $cart = [ ];
        }

        return $cart;
    }

    /**
     * @return array
     */
    protected function getHelpSettings() {
        $this->em      = $this->get( 'doctrine.orm.entity_manager' );
        $_helpSettings = $this->em->getRepository( 'AppBundle:Settings' )->findOneByName( 'help_settings' );
        $helpSettings  = [ ];
        if ( $_helpSettings instanceof Settings ) {
            $helpSettings = unserialize( $_helpSettings->getSettings() );
        }

        return $helpSettings;
    }

    /**
     * @return array
     */
    protected function getCategoryMenuBlock() {
        $settings = $this->getSiteSettings();

        return $this->get( 'blocks.service' )->getCategoriesBlock( $settings );
    }

    /**
     * @return array
     */
    protected function getToDoBlock() {
        return [ 'show' => 1 ];
    }

    /**
     * @return array
     */
    protected function getReviewsBlock() {
        return $this->em->getRepository( 'AppBundle:Review' )->getLatestReviews();
    }

    /**
     * @return array
     */
    protected function getPopularBlock() {
        return $this->em->getRepository( 'AppBundle:Popular' )->findBy( [ ] );
    }

    /**
     * @return array
     */
    protected function getMainMenuBlock() {
        return $this->get( 'blocks.service' )->getMainMenuBlock();
    }

    /**
     * @return array
     */
    protected function getSimilarBlock() {
        return $this->em->getRepository( 'AppBundle:Picture' )->getActiveSimilar( $this->id );
    }

    /**
     * @return array
     */
    protected function getBreadCrumbBlock() {
        return $this->get( 'app.breadcrumb_service' )->buildBreadCrumb( $this->pageSlug, $this->pageType );
    }

    /**
     * @return array
     */
    protected function getLastVisitedBlock() {
        $lastVisited = $this->get( 'app.session_manager' )->getLastVisitedItems();
        if ( $lastVisited ) {
            $lastVisited = $this->em->getRepository( 'AppBundle:Picture' )->findLastVisited( $lastVisited );
        }

        return $lastVisited;
    }

    /**
     * @return array
     */
    protected function getDeferredBlock() {
        $deferred = $this->get( 'app.session_manager' )->getDeferredItems();
        if ( $deferred ) {
            $deferred = $this->em->getRepository( 'AppBundle:Picture' )->findDeferred( $deferred );
        }

        return $deferred;
    }

    /**
     * @return array
     */
    protected function getDeferred() {
        return $this->get( 'app.session_manager' )->getDeferredItems();
    }
}