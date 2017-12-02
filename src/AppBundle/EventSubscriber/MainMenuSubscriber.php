<?php
namespace AppBundle\EventSubscriber;

use AppBundle\Entity\MainMenu;
use AppBundle\Entity\Image;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class MainMenuSubscriber
 */
class MainMenuSubscriber
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * MainMenuSubscriber constructor.
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
        if($entity instanceof MainMenu && $image = $entity->getImage()) {
            $this->createResize($entity, $image);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if($entity instanceof MainMenu && $image = $entity->getImage()) {
            $this->createResize($entity, $image);
        }
    }

    /**
     * @param MainMenu $material
     * @param Image $image
     */
    private function createResize(MainMenu $material, Image $image) {

        if($image->getOriginFile() && file_exists($image->getOriginFile())) {
            $this->container->get('helper.imageresizer')
                ->copyImage($image->getOriginFile(), $image->getBasePath(), $image->getFilename());
            $this->container->get('helper.imageresizer')
                ->resizeImage($image->getOriginFile(), $image->getSmallThumbBasePath(), $image::THUMB_SMALL_IMAGE_HEIGHT, $image::THUMB_SMALL_IMAGE_WIDTH);
            unlink($image->getOriginFile());
        }
    }
}
