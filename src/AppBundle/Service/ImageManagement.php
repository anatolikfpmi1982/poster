<?php
namespace AppBundle\Service;

use AppBundle\Entity\Image;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;

class ImageManagement
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * ImageManagement constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     *  Clean unnecessary images.
     */
    public function cleanGarbageImages(){

        $rsm = new ResultSetMapping;
        $rsm->addEntityResult('AppBundle:Image', 'u');
        $rsm->addFieldResult('u', 'id', 'id');
        $rsm->addFieldResult('u', 'filename', 'filename');
        $rsm->addFieldResult('u', 'created_at', 'createdAt');
        $rsm->addFieldResult('u', 'updated_at', 'updatedAt');
        // build rsm here

        $query = $this->em->createNativeQuery('
SELECT id, filename, created_at, updated_at
FROM images
WHERE id NOT IN (SELECT DISTINCT image_id FROM frames_images WHERE image_id IS NOT NULL)
AND id NOT IN (SELECT DISTINCT image_id FROM slider WHERE image_id IS NOT NULL)
AND id NOT IN (SELECT DISTINCT image_id FROM underframes WHERE image_id IS NOT NULL)
AND id NOT IN (SELECT DISTINCT image_id FROM frame_materials WHERE image_id IS NOT NULL)
AND id NOT IN (SELECT DISTINCT image_id FROM banner_materials WHERE image_id IS NOT NULL)
AND id NOT IN (SELECT DISTINCT image_id FROM module_types WHERE image_id IS NOT NULL)
AND id NOT IN (SELECT DISTINCT image_id FROM main_menu_items WHERE image_id IS NOT NULL)
AND id NOT IN (SELECT DISTINCT image_id FROM own_pictures WHERE image_id IS NOT NULL)
AND id NOT IN (SELECT DISTINCT image_id FROM picture_forms WHERE image_id IS NOT NULL)
AND id NOT IN (SELECT DISTINCT image_id FROM pictures WHERE image_id IS NOT NULL)', $rsm);

        $images = $query->getResult();
        if(count($images) > 0) {
            $this->deleteImages($images);
        }
    }

    /**
     * Delete images by ORM.
     * @param $images
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteImages($images){
        if (count($images) > 0) {
            foreach($images as $image) {
                /** @var Image $image  */
                $this->em->remove($image);
                $image->onPreRemove();
                $this->em->flush();
            }
        }
    }

    /**
     * Delete image by ORM.
     * @param $image
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteImage($image){
        /** @var Image $image  */
        $this->em->remove($image);
        $image->onPreRemove();
        $this->em->flush();
    }
}