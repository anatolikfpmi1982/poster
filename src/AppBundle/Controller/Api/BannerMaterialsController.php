<?php

namespace AppBundle\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BannerMaterialsController extends Controller implements ClassResourceInterface
{
    /**
     * Collection get action
     * @var Request $request
     * @return array
     */
    public function cgetAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $entities = $em->getRepository('AppBundle:FrameMaterial')->findBy(['isActive' => true]);

        return $entities;
    }
}