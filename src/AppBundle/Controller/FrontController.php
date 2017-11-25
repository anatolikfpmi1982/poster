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
//        return $this->get( 'blocks.service' )->getPopularBlock();
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
    protected function getSliderBlock() {
        return $this->get( 'blocks.service' )->getSliderItems();
    }
}