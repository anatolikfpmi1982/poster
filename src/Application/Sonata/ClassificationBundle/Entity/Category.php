<?php

/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\ClassificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonata\ClassificationBundle\Entity\BaseCategory as BaseCategory;

/**
 * This file has been generated by the Sonata EasyExtends bundle.
 *
 * @link https://sonata-project.org/bundles/easy-extends
 *
 * References :
 *   working with object : http://www.doctrine-project.org/projects/orm/2.0/docs/reference/working-with-objects/en
 *
 * @author <yourname> <youremail>
 */
class Category extends BaseCategory {
    /**
     * @var int $id
     */
    protected $id;

    /**
     * Get id
     *
     * @return int $id
     */
    public function getId() {
        return $this->id;
    }
}
