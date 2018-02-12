<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SearchController
 */
class SearchController extends FrontController
{
    const PAGE_LIMIT = 20;

    /**
     * @param Request $request
     *
     * @Route("/search", name="search")
     *
     * @throws \LogicException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Request $request)
    {
        $this->blocks['LastVisited'] = 6;
        $this->menu = '/search';
        $searchString = $request->query->get('query');
        $this->pageSlug = htmlspecialchars($searchString);
        $this->pageType = 'search';
        $this->doBlocks();
        $queryBuilder = $this->em->getRepository('AppBundle:Picture')->getActivePicturesForSearch($searchString);
        $query = $queryBuilder->getQuery();
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            self::PAGE_LIMIT/*limit per page*/
        );

        $this->data['pagination'] = $pagination;
        $this->data['searchString'] = $this->pageSlug;
        $this->data['filters']['tpls'] = $this->em->getRepository('AppBundle:PictureForm')->findBy(['isActive' => true]);

        // parameters to template
        return $this->render('AppBundle:Search:show.html.twig', $this->data);
    }

}
