<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CategoriesController extends Controller
{
    /**
     * @Route("/category/{slug}", name="category")
     */
    public function showAction($slug, Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $category = $em->getRepository('AppBundle:Category')->findOneBySlug($slug);
        $category->count = count($category->getPictures());

        $queryBuilder = $em->getRepository('AppBundle:Picture')->getActivePicturesFromCategory($category->getId());
        $query = $queryBuilder->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        $lastVisited = $this->get('app.session_manager')->getLastVisitedItems();

        if($lastVisited) {
            $lastVisited = $em->getRepository('AppBundle:Picture')->findLastVisited($lastVisited);
        }

        // parameters to template
        return $this->render('AppBundle:Categories:show.html.twig', array('pagination' => $pagination, 'category' => $category, 'lastVisited' => $lastVisited));
    }

}
