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
     * @param array $data
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

        return !empty( $cart[ $id ] ) ? $cart[ $id ] : null;
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

    /**
     * Set own picture
     *
     * @param integer $id
     */
    public function addMyFile( $id ) {
        $myFiles = $this->session->get( 'my_files' );
        $myFiles[] = $id;

        $this->session->set( 'my_files', $myFiles );
    }

    /**
     * Get own picture
     *
     * @param integer $id
     * @return null|integer
     */
    public function getMyFilesItem( $id ) {
        $this->session->get( 'my_files' );

        foreach($this->session->get( 'my_files' ) as $k => $v) {
            if($v == $id) {
                return $v;
            }
        }

        return null;
    }

    /**
     * Get my files
     *
     * @return null|array
     */
    public function getMyFiles() {
        return $this->session->get( 'my_files' );
    }

    /**
     * Get my files count
     *
     * @return null|array
     */
    public function getMyFilesCount() {
        return !empty($this->session->get( 'my_files' )) ? count($this->session->get( 'my_files' )) : '';
    }

    /**
     * Delete my file
     *
     * @param int $id
     */
    public function deleteFromMyFiles( $id ) {
        if ( $myFiles = $this->session->get( 'my_files' ) ) {
            foreach ($myFiles as $k => $v) {
                if($v == $id) {
                    unset($myFiles[$k]);
                }
            }

            $this->session->set( 'my_files', $myFiles );
        }
    }

    /**
     * Clear my files
     */
    public function clearMyFiles() {
        $this->session->set( 'my_files', null );
    }

    /**
     * Clear cart
     */
    public function clearCart() {
        $this->session->set( 'cart', null );
    }
}