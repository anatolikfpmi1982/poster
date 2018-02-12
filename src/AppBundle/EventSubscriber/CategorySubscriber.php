<?php
namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Category3;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CategorySubscriber
 */
class CategorySubscriber
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * PictureSubscriber constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if($entity instanceof Category3) {
            $entity->setSlug($this->container->get('helper.slugcreator')->createSlug($entity->getTitle()));
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if($entity instanceof Category3) {
            $entity->setSlug($this->container->get('helper.slugcreator')->createSlug($entity->getTitle()));
        }
    }
}
