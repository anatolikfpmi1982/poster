<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Review;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ReviewsController
 */
class ReviewsController extends FrontController {

    const PAGE_LIMIT = 5;

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

    /**
     *
     * @Route("/ajax/review/add", name="ajax_reviews_add")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxAddAction( Request $request ) {
        $result = false;
        if ($request->getMethod() == 'POST') {
            $name = $request->request->get('name');
            $email = $request->request->get('email');
            $city = $request->request->get('city');
            $review = $request->request->get('review');
        }

        if(!empty($name) && !empty($email) && !empty($review)) {
            $reviewEntity = new Review();
            $reviewEntity->setName($name);
            $reviewEntity->setSlug($this->container->get('helper.slugcreator')->createSlug($name));
            $reviewEntity->setEmail($email);
            $reviewEntity->setReview($review);
            $reviewEntity->setIsActive(false);
            $reviewEntity->setCreatedAt(new \DateTime());
            $reviewEntity->setUpdatedAt(new \DateTime());
            if(!empty($city)) {
                $reviewEntity->setCity($city);
            }

            $this->get( 'doctrine.orm.entity_manager' )->persist($reviewEntity);
            $this->get( 'doctrine.orm.entity_manager' )->flush();

            $result = true;
        }

        // parameters to template
        return new JsonResponse(array('result' => $result));
    }

}
