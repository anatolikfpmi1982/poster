<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class ReviewsController
 */
class ReviewsController extends Controller
{
    /**
     * @Route("/reviews", name="reviews")
     */
    public function indexAction(Request $request)
    {
        $em    = $this->get('doctrine.orm.entity_manager');

        $queryBuilder = $em->getRepository('AppBundle:Review')->createQueryBuilder('r')->where('r.isActive = true');
        $query = $queryBuilder->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            3/*limit per page*/
        );

        // parameters to template
        return $this->render('AppBundle:Reviews:index.html.twig', array('pagination' => $pagination));
    }

}
