<?php
namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Frame;
use AppBundle\Entity\Image;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class FrameSubscriber
 */
class FrameSubscriber
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * FrameSubscriber constructor.
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
        if($entity instanceof Frame && count($entity->getImages())>0) {
            $images = $entity->getImages();
            foreach($images as $image) {
                $this->createResize($entity, $image);
            }
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if($entity instanceof Frame && count($entity->getImages())>0) {
            $images = $entity->getImages();
            foreach($images as $image) {
                $this->createResize($entity, $image);
            }
        }
    }

    /**
     * @param Frame $frame
     * @param Image $image
     */
    private function createResize(Frame $frame, Image $image) {

        if($image->getOriginFile() && file_exists($image->getOriginFile())) {
            $this->container->get('helper.imageresizer')
                ->resizeImage($image->getOriginFile(), $image->getThumbBasePath(), $frame::THUMB_IMAGE_HEIGHT, $frame::THUMB_IMAGE_WIDTH);
            $this->container->get('helper.imageresizer')
                ->resizeImage($image->getOriginFile(), $image->getMiniThumbBasePath(), $frame::THUMB_MINI_IMAGE_HEIGHT, $frame::THUMB_MINI_IMAGE_WIDTH);
            $this->container->get('helper.imageresizer')
                ->resizeImage($image->getOriginFile(), $image->getSmallThumbBasePath(), $image::THUMB_SMALL_IMAGE_HEIGHT, $image::THUMB_SMALL_IMAGE_WIDTH);
            $this->container->get('helper.imageresizer')
                ->resizeImage($image->getOriginFile(), $image->getBasePath(), $frame::IMAGE_HEIGHT, $frame::IMAGE_WIDTH);
            unlink($image->getOriginFile());
        }
    }
}
