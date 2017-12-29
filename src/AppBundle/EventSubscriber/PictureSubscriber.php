<?php
namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Image;
use AppBundle\Entity\Picture;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class PictureSubscriber
 */
class PictureSubscriber
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
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if($entity instanceof Picture) {
            if($image = $entity->getImage()) {
                $this->createResize($entity, $image);
            }
            if($image = $entity->getImageBanner()) {
                $form = $this->container->get('helper.imageformidentifier')->identify($entity->getImageBanner());
                if($formEntity = $this->container->get('doctrine.orm.entity_manager')->getRepository('AppBundle:PictureForm')->findOneBy(['serviceName' => $form])) {
                    $entity->setForm($formEntity);
                }

                $this->createResize($entity, $image);
            }
            $entity->generateCode();
            $entity->setSlug($this->container->get('helper.slugcreator')->createSlug($entity->getTitle()));
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if($entity instanceof Picture) {
            if($image = $entity->getImage()) {
                $this->createResize($entity, $image);
            }
            if($image = $entity->getImageBanner()) {
                $this->createResize($entity, $image);
            }
            $entity->setSlug($this->container->get('helper.slugcreator')->createSlug($entity->getTitle()));
        }
    }

    /**
     * @param Picture $picture
     * @param Image $image
     */
    private function createResize(Picture $picture, Image $image) {

        if($image->getOriginFile() && file_exists($image->getOriginFile())) {
            $this->container->get('helper.imageresizer')
                ->resizeImage($image->getOriginFile(), $image->getThumbBasePath(), $picture::THUMB_IMAGE_HEIGHT, $picture::THUMB_IMAGE_WIDTH);
            $this->container->get('helper.imageresizer')
                ->resizeImage($image->getOriginFile(), $image->getMiniThumbBasePath(), $picture::THUMB_MINI_IMAGE_HEIGHT, $picture::THUMB_MINI_IMAGE_WIDTH);
            $this->container->get('helper.imageresizer')
                ->resizeImage($image->getOriginFile(), $image->getBasePath(), $picture::IMAGE_HEIGHT, $picture::IMAGE_WIDTH);
            $this->container->get('helper.imageresizer')
                ->resizeImage($image->getOriginFile(), $image->getSmallThumbBasePath(), $image::THUMB_SMALL_IMAGE_HEIGHT, $image::THUMB_SMALL_IMAGE_WIDTH);
            unlink($image->getOriginFile());
        }
    }
}
