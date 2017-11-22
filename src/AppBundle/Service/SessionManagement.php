<?php
namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SessionManagement
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function addLastVisitedItem($id)
    {
        $session = $this->container->get('session');
        if($lastVisited = $session->get('lastVisited')) {
            if(count($lastVisited) >= 5) {
                array_pop($lastVisited);
            }
            if(in_array($id, $lastVisited)) {
                unset($lastVisited[array_search($id, $lastVisited)]);
            }
            array_unshift($lastVisited, $id);
        } else {
            $lastVisited = [$id];
        }
        $session->set('lastVisited', $lastVisited);
    }

    public function getLastVisitedItems()
    {
        return $this->container->get('session')->get('lastVisited');
    }
}