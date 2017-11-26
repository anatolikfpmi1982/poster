<?php
namespace AppBundle\EventSubscriber;

use AppBundle\Entity\BannerMaterial;
use AppBundle\Entity\Image;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BannerMaterialSubscriber
 */
class BannerMaterialSubscriber
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * BannerMaterialSubscriber constructor.
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
        if($entity instanceof BannerMaterial && $image = $entity->getImage()) {
            $this->createResize($entity, $image);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if($entity instanceof BannerMaterial && $image = $entity->getImage()) {
            $this->createResize($entity, $image);
        }
    }

    /**
     * @param BannerMaterial $material
     * @param Image $image
     */
    private function createResize(BannerMaterial $material, Image $image) {

        if($image->getOriginFile() && file_exists($image->getOriginFile())) {
            $this->container->get('helper.imageresizer')
                ->resizeImage($image->getOriginFile(), $image->getThumbBasePath(), $material::THUMB_IMAGE_HEIGHT, $material::THUMB_IMAGE_WIDTH);
            $this->container->get('helper.imageresizer')
                ->resizeImage($image->getOriginFile(), $image->getMiniThumbBasePath(), $material::THUMB_MINI_IMAGE_HEIGHT, $material::THUMB_MINI_IMAGE_WIDTH);
            $this->container->get('helper.imageresizer')
                ->resizeImage($image->getOriginFile(), $image->getBasePath(), $material::IMAGE_HEIGHT, $material::IMAGE_WIDTH);
            unlink($image->getOriginFile());
        }
    }
}
