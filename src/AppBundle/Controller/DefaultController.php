<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 */
class DefaultController extends FrontController {

    /**
     * @Route("/", name="homepage")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction( Request $request ) {
        $this->blocks = [ 'CategoryMenu' => 1, 'Reviews' => 2, 'MainMenu' => 3, 'Popular' => 4 ];
        $this->menu = '/';
        $this->doBlocks();
        $this->data['sliders'] = $this->get( 'blocks.service' )->getSliderItems();
        // replace this example code with whatever you need
        return $this->render( 'AppBundle:Main:index.html.twig', $this->data );
    }
}
