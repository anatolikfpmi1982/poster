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
     * SessionManagement constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct( ContainerInterface $container ) {
        $this->container = $container;
    }

    /**
     * @param int $id
     */
    public function addLastVisitedItem( $id ) {
        $session = $this->container->get( 'session' );
        if ( $lastVisited = $session->get( 'lastVisited' ) ) {
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
        $session->set( 'lastVisited', $lastVisited );
    }

    /**
     * @param int $id
     */
    public function addDeferredItem( $id ) {
        $session = $this->container->get( 'session' );
        if ( $deferred = $session->get( 'deferred' ) ) {
            if ( !in_array( $id, $deferred, false ) ) {
                $deferred[] = $id;
            }
        } else {
            $deferred = [ $id ];
        }
        $session->set( 'deferred', $deferred );
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
}