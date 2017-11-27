<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class PageController
 */
class PageController extends Controller
{
    /**
     * @Route("/page/{slug}", name="page")
     */
    public function showAction($slug, Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $page = $em->getRepository('AppBundle:Page')->findOneBy(['slug' => $slug, 'isActive' => true]);

        // parameters to template
        return $this->render('AppBundle:Page:index.html.twig', ['page' => $page]);
    }

}
