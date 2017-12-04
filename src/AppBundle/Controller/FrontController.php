<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Settings;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class FrontController
 */
class FrontController extends Controller {

    /**
     * @var array
     */
    protected $data = [ ];

    /**
     * @var array
     */
    protected $blocks = [ ];

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
     *
     */
    public function doBlocks() {
        if ( $this->blocks && count( $this->blocks ) > 0 ) {
            asort( $this->blocks );
            foreach ( $this->blocks as $block => $order ) {
                $function = 'get' . $block . 'Block';
                if ( method_exists( $this, $function ) ) {
                    $this->data['site_blocks'][ $block ] = $this->$function();
                }
            }
        }

        $this->data['active_menu'] = $this->menu;
        $this->data['site_settings'] = $this->getSiteSettings();
    }

    /**
     * @return array
     */
    protected function getSiteSettings() {
        $_siteSettings = $this->get( 'doctrine.orm.entity_manager' )->getRepository( 'AppBundle:Settings' )->findOneByName('site_settings');
        $siteSettings = [];
        if($_siteSettings instanceof Settings) {
            $siteSettings = unserialize($_siteSettings->getSettings());
        }
        return $siteSettings;
    }

    /**
     * @return array
     */
    protected function getCategoryMenuBlock() {
        return $this->get( 'blocks.service' )->getCategoriesBlock();
    }

    /**
     * @return array
     */
    protected function getReviewsBlock() {
        return $this->get( 'doctrine.orm.entity_manager' )->getRepository( 'AppBundle:Review' )->getLatestReviews();
    }

    /**
     * @return array
     */
    protected function getPopularBlock() {
        return $this->get( 'doctrine.orm.entity_manager' )->getRepository( 'AppBundle:Popular' )->findBy( [ ] );
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
        return $this->get( 'doctrine.orm.entity_manager' )->getRepository( 'AppBundle:Picture' )->getActiveSimilar($this->id);
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
        $lastVisited = $this->get('app.session_manager')->getLastVisitedItems();
        if($lastVisited) {
            $lastVisited = $this->get( 'doctrine.orm.entity_manager' )->getRepository('AppBundle:Picture')->findLastVisited($lastVisited);
        }

        return $lastVisited;
    }
}