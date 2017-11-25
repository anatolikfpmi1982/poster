<?php
namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Image;
use AppBundle\Entity\Underframe;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UnderframeSubscriber
 */
class UnderframeSubscriber
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * UnderframeSubscriber constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if($entity instanceof Underframe && $image = $entity->getImage()) {
            $this->createResize($entity, $image);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if($entity instanceof Underframe && $image = $entity->getImage()) {
            $this->createResize($entity, $image);
        }
    }

    /**
     * @param Underframe $underframe
     * @param Image $image
     */
    private function createResize(Underframe $underframe, Image $image) {

        if($image->getOriginFile() && file_exists($image->getOriginFile())) {
            $this->container->get('helper.imageresizer')
                ->resizeImage($image->getOriginFile(), $image->getThumbBasePath(), $underframe::THUMB_IMAGE_HEIGHT, $underframe::THUMB_IMAGE_WIDTH);
            $this->container->get('helper.imageresizer')
                ->resizeImage($image->getOriginFile(), $image->getMiniThumbBasePath(), $underframe::THUMB_MINI_IMAGE_HEIGHT, $underframe::THUMB_MINI_IMAGE_WIDTH);
            $this->container->get('helper.imageresizer')
                ->resizeImage($image->getOriginFile(), $image->getBasePath(), $underframe::IMAGE_HEIGHT, $underframe::IMAGE_WIDTH);
            unlink($image->getOriginFile());
        }
    }
}
