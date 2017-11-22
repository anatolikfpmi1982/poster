<?php

/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

use Sonata\ClassificationBundle\Entity\BaseContext as BaseContext;
use Doctrine\ORM\Mapping as ORM;

/**
 * Context
 *
 * @ORM\Table(name="classification__context")
 * @ORM\Entity(repositoryClass="Doctrine\ORM\EntityRepository")
 */
class Context extends BaseContext
{
    /**
     * @var int $id
     *
     * @ORM\Column(name="id", type="string")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    protected $id;

    /**
     * Get id
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }
}
