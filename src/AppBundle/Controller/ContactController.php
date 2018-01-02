<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Review;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ContactController
 */
class ContactController extends FrontController {

    /**
     * @Route("/contact_us", name="contact_us")
     */
    public function indexAction( Request $request ) {

        $this->blocks   = [ 'CategoryMenu' => 1, 'Reviews' => 2, 'MainMenu' => 3, 'BreadCrumb' => 4 ];
        $this->menu     = '/reviews';
        $this->pageType = 'review';
        $this->doBlocks();

        // parameters to template
        return $this->render( 'AppBundle:Contact:index.html.twig', $this->data );
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
