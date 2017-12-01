<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CategoriesController
 */
class CategoriesController extends FrontController
{
    /**
     * @param string $slug
     * @param Request $request
     *
     * @Route("/category/{slug}", name="category")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($slug, Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $category = $em->getRepository('AppBundle:Category3')->findOneBySlug($slug);
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

        $this->blocks = [ 'CategoryMenu' => 1, 'Popular' => 2 , 'MainMenu' => 3, 'BreadCrumb' => 4 ];
        $this->menu = '/';
        $this->pageSlug = $slug;
        $this->pageType = 'category';
        $this->doBlocks();
        $this->data['pagination'] = $pagination;
        $this->data['category'] = $category;
        $this->data['lastVisited'] = $lastVisited;

        // parameters to template
        return $this->render('AppBundle:Categories:show.html.twig', $this->data);
    }

}
