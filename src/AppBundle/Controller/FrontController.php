<?php
namespace AppBundle\Controller;

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
    protected function getPopularBlock() {
        return $this->get( 'doctrine.orm.entity_manager' )->getRepository( 'AppBundle:Review' )->getLatestReviews();
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
    protected function getBreadCrumbBlock() {
        return $this->get( 'app.breadcrumb_service' )->buildBreadCrumb( $this->pageSlug, $this->pageType );
    }
}