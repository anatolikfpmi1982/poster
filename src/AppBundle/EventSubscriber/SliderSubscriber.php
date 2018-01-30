<?php
namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Image;
use AppBundle\Entity\SliderItem;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SliderSubscriber
 */
class SliderSubscriber {
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * SliderSubscriber constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct( ContainerInterface $container ) {
        $this->container = $container;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist( LifecycleEventArgs $args ) {
        $entity = $args->getEntity();
        if ( $entity instanceof SliderItem && $image = $entity->getImage() ) {
            $this->createResize( $entity, $image );
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate( LifecycleEventArgs $args ) {
        $entity = $args->getEntity();
        if ( $entity instanceof SliderItem && $image = $entity->getImage() ) {
            $this->createResize( $entity, $image );
        }
    }

    /**
     * @param SliderItem $slider
     * @param Image $image
     */
    private function createResize( SliderItem $slider, Image $image ) {

        if ( $image->getOriginFile() && file_exists( $image->getOriginFile() ) ) {
            $this->container->get( 'helper.imageresizer' )
                            ->resizeImage( $image->getOriginFile(), $image->getThumbBasePath(), $slider::THUMB_IMAGE_HEIGHT, $slider::THUMB_IMAGE_WIDTH );
            $this->container->get( 'helper.imageresizer' )
                            ->resizeImage( $image->getOriginFile(), $image->getMaxThumbBasePath(), $slider::THUMB_MAX_IMAGE_HEIGHT,
                                $slider::THUMB_MAX_IMAGE_WIDTH, true );
            $this->container->get('helper.imageresizer')
                            ->resizeImage($image->getOriginFile(), $image->getSmallThumbBasePath(),
                                $image::THUMB_SMALL_IMAGE_HEIGHT, $image::THUMB_SMALL_IMAGE_WIDTH);
            $this->container->get('helper.imageresizer')
                            ->resizeImage($image->getOriginFile(), $image->getMiniThumbBasePath(),
                                $image::THUMB_MINI_IMAGE_HEIGHT, $image::THUMB_MINI_IMAGE_WIDTH);
            unlink( $image->getOriginFile() );
        }
    }
}
