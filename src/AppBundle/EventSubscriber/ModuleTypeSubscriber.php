<?php
namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Image;
use AppBundle\Entity\ModuleType;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ModuleTypeSubscriber
 */
class ModuleTypeSubscriber
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * ModuleTypeSubscriber constructor.
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
        if($entity instanceof ModuleType && $image = $entity->getImage()) {
            $this->createResize($entity, $image);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if($entity instanceof ModuleType && $image = $entity->getImage()) {
            $this->createResize($entity, $image);
        }
    }

    /**
     * @param ModuleType $moduleType
     * @param Image $image
     */
    private function createResize(ModuleType $moduleType, Image $image) {

        if($image->getOriginFile() && file_exists($image->getOriginFile())) {
            $this->container->get('helper.imageresizer')
                ->copyImage($image->getOriginFile(), $image->getBasePath(), $image->getFilename());
            $this->container->get('helper.imageresizer')
                ->resizeImage($image->getOriginFile(), $image->getMiniThumbBasePath(), $image::THUMB_MINI_IMAGE_HEIGHT, $image::THUMB_MINI_IMAGE_WIDTH);
            $this->container->get('helper.imageresizer')
                ->resizeImage($image->getOriginFile(), $image->getSmallThumbBasePath(), $image::THUMB_SMALL_IMAGE_HEIGHT, $image::THUMB_SMALL_IMAGE_WIDTH);
            unlink($image->getOriginFile());
        }
    }
}
