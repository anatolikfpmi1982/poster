<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PicturesController extends Controller
{
    /**
     * @Route("/picture/{id}", name="picture")
     */
    public function showAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $picture = $em->getRepository('AppBundle:Picture')->find($id);

        $this->get('app.session_manager')->addLastVisitedItem($picture->getId());

        // parameters to template
        return $this->render('AppBundle:Pictures:show.html.twig', array('picture' => $picture));
    }

}
