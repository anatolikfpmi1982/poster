<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Review;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ReviewsController
 */
class ReviewsController extends FrontController {

    const PAGE_LIMIT = 5;

    /**
     * @Route("/reviews", name="reviews")
     *
     * @throws \LogicException
     *
     */
    public function indexAction( Request $request ) {
        $em           = $this->get( 'doctrine.orm.entity_manager' );
        $queryBuilder = $em->getRepository( 'AppBundle:Review' )->getActiveReviews();
        $query        = $queryBuilder->getQuery();

        $paginator  = $this->get( 'knp_paginator' );
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt( 'page', 1 )/*page number*/,
            self::PAGE_LIMIT/*limit per page*/
        );

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

            $settings = $this->getSiteSettings();

            if(!empty($settings['from_email']) && !empty('to_email')) {
                $message = (new \Swift_Message('Новый заказ'))
                    ->setFrom($settings['from_email'])
                    ->setTo($settings['to_email'])
                    ->setBody(
                        $this->renderView(
                            'Emails/review.html.twig',
                            array('name' => $name, 'email' => $email, 'city' => $city, 'review' => $review)
                        ),
                        'text/html'
                    );

                $this->get('mailer')->send($message);
            }

            $result = true;
        }

        // parameters to template
        return new JsonResponse(['result' => (int)$result]);
    }

}
