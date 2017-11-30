<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PageController
 */
class PageController extends FrontController {
    /**
     * @param string $slug
     * @param Request $request
     *
     * @Route("/page/{slug}", name="page")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction( $slug, Request $request ) {
        $em   = $this->get( 'doctrine.orm.entity_manager' );
        $page = $em->getRepository( 'AppBundle:Page' )->findOneBy( [ 'slug' => $slug, 'isActive' => true ] );

        $this->blocks = [ 'CategoryMenu' => 1, 'Popular' => 2 , 'MainMenu' => 3 ];
        $this->menu = '/';
        $this->doBlocks();
        $this->data['page'] = $page;

        // parameters to template
        return $this->render( 'AppBundle:Page:index.html.twig', $this->data );
    }

}
