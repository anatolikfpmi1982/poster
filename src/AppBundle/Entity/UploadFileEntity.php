<?php
/**
 * Created by PhpStorm.
 * User: Anatoli
 * Date: 08.05.2017
 * Time: 18:12
 */

namespace AppBundle\Entity;


abstract class UploadFileEntity
{
    /**
     * @var string
     */
    protected $entityName;

    /**
     * @param string $entityName
     * @return string
     */
    abstract public function setEntityName($entityName);
}