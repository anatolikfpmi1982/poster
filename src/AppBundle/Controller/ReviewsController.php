<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ReviewsController
 */
class ReviewsController extends FrontController {

    const PAGE_LIMIT = 3;

    /**
     * @Route("/reviews", name="reviews")
     */
    public function indexAction( Request $request ) {
        $em           = $this->get( 'doctrine.orm.entity_manager' );
        $queryBuilder = $em->getRepository( 'AppBundle:Review' )->createQueryBuilder( 'r' )->where( 'r.isActive = true' );
        $query        = $queryBuilder->getQuery();

        $paginator  = $this->get( 'knp_paginator' );
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt( 'page', 1 )/*page number*/,
            self::PAGE_LIMIT/*limit per page*/
        );

        $this->blocks   = [ 'CategoryMenu' => 1, 'Reviews' => 2, 'MainMenu' => 3, 'BreadCrumb' => 4 ];
        $this->menu     = '/reviews';
        $this->pageType = 'review';
        $this->doBlocks();

        $this->data['pagination'] = $pagination;

        // parameters to template
        return $this->render( 'AppBundle:Reviews:index.html.twig', $this->data );
    }

}
