<?php
namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SessionManagement
 */
class SessionManagement {
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var object
     */
    private $session;

    /**
     * SessionManagement constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct( ContainerInterface $container ) {
        $this->container = $container;
        $this->session   = $session = $this->container->get( 'session' );
    }

    /**
     * @param int $id
     */
    public function addLastVisitedItem( $id ) {
        if ( $lastVisited = $this->session->get( 'lastVisited' ) ) {
            if ( count( $lastVisited ) >= 5 ) {
                array_pop( $lastVisited );
            }
            if ( in_array( $id, $lastVisited, false ) ) {
                unset( $lastVisited[ array_search( $id, $lastVisited, false ) ] );
            }
            array_unshift( $lastVisited, $id );
        } else {
            $lastVisited = [ $id ];
        }
        $this->session->set( 'lastVisited', $lastVisited );
    }

    /**
     * Add deferred picture id to session
     *
     * @param int $id
     */
    public function addDeferredItem( $id ) {
        if ( $deferred = $this->session->get( 'deferred' ) ) {
            if ( $id && ! in_array( $id, $deferred, false ) ) {
                $deferred[] = $id;
            }
        } else {
            $deferred = [ $id ];
        }
        $this->session->set( 'deferred', $deferred );
    }

    /**
     * Delete deferred picture id from session
     *
     * @param int $id
     */
    public function deleteDeferredItem( $id ) {
        if ( $deferred = $this->session->get( 'deferred' ) ) {
            if ( ( $index = array_search( $id, $deferred, false ) ) !== null && $id ) {
                unset( $deferred[ $index ] );
            }

            $this->session->set( 'deferred', $deferred );
        }
    }

    /**
     * Add picture to cart
     *
     * @param object $data
     */
    public function addToCart( $data ) {
        $cart                = $this->session->get( 'cart' );
        $cart[ $data['id'] ] = $data;

        $this->session->set( 'cart', $cart );
    }

    /**
     * Get picture from cart
     *
     * @param string $id
     *
     * @return array
     **/
    public function getFromCart( $id ) {
        $cart = $this->session->get( 'cart' );

        return ! empty( $cart[ $id ] ) ? $cart[ $id ] : null;
    }

    /**
     * Get cart count
     *
     * @return integer
     **/
    public function getCartCount() {
        $cart = $this->session->get( 'cart' );

        return ! empty( $cart ) ? count( $cart ) : 0;
    }

    /**
     * Delete picture from cart
     *
     * @param int $id
     */
    public function deleteFromCart( $id ) {
        if ( $cart = $this->session->get( 'cart' ) ) {
            unset( $cart[ $id ] );

            $this->session->set( 'cart', $cart );
        }
    }

    /**
     * Clean cart
     */
    public function cleanCart() {
        if ( $cart = $this->session->get( 'cart' ) ) {
            $this->session->set( 'cart', [ ] );
        }
    }


    /**
     * @return mixed
     */
    public function getLastVisitedItems() {
        return $this->container->get( 'session' )->get( 'lastVisited' );
    }

    /**
     * @return mixed
     */
    public function getDeferredItems() {
        return $this->container->get( 'session' )->get( 'deferred' );
    }

    /**
     * @return mixed
     */
    public function getDeferredCount() {
        $deferred = $this->container->get( 'session' )->get( 'deferred' );
        return ! empty( $deferred ) ? count( $deferred ) : '';
    }

    /**
     * @return array
     */
    public function getCart() {
        return (array) $this->container->get( 'session' )->get( 'cart' );
    }
}