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
WHERE id NOT IN (SELECT DISTINCT image_id FROM frames_images)
AND id NOT IN (SELECT DISTINCT image_id FROM slider)
AND id NOT IN (SELECT DISTINCT image_id FROM underframes)
AND id NOT IN (SELECT DISTINCT image_id FROM frame_materials)
AND id NOT IN (SELECT DISTINCT image_id FROM banner_materials)
AND id NOT IN (SELECT DISTINCT image_id FROM module_types)
AND id NOT IN (SELECT DISTINCT image_id FROM pictures)', $rsm);

        $images = $query->getResult();

        if(count($images) > 0) {
            $this->deleteImages($images);
        }
    }

    /**
     * Delete images by ORM.
     * @param $images
     */
    public function deleteImages($images){
        if (count($images) > 0) {
            foreach($images as $image) {
                /** @var Image $image  */
                $this->em->remove($image);
                $image->onPreRemove();
            }
            $this->em->flush();
        }
    }
}