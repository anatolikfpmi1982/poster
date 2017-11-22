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
        $this->blocks = [ 'CategoryMenu' => 1, 'Popular' => 2 ];
        $this->doBlocks();
        // replace this example code with whatever you need
        return $this->render( 'AppBundle:Main:index.html.twig', $this->data );
    }
}
