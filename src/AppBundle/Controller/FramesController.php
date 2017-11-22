<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class FramesController
 */
class FramesController extends Controller
{
    /**
     * @Route("/frames", name="frames")
     */
    public function listAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $queryBuilder = $em->getRepository('AppBundle:Frame')->createQueryBuilder('f')->where('f.isActive = true');
        $query = $queryBuilder->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            3/*limit per page*/
        );

        // parameters to template
        return $this->render('AppBundle:Frames:list.html.twig', array('pagination' => $pagination));
    }

    /**
     *
     * @Route("/frame/{id}", name="frame")
     */
    public function showAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $frame = $em->getRepository('AppBundle:Frame')->find($id);

        // parameters to template
        return $this->render('AppBundle:Frames:show.html.twig', array('frame' => $frame));
    }


}
