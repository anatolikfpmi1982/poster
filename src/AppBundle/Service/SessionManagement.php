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
        $this->session = $session = $this->container->get( 'session' );
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
            if ( !in_array( $id, $deferred, false ) ) {
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
            if ( ($index = array_search( $id, $deferred, false )) !== null ) {
                unset($deferred[$index]);
            }

            $this->session->set( 'deferred', $deferred );
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
}