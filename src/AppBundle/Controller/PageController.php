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

        $this->menu = '/page/' . $page->getSlug();
        $this->pageSlug = $slug;
        $this->pageType = 'static_page';
        $this->doBlocks();
        $page->setBody($this->get('helper.textformater')->formatMoreText($page->getBody()));
        $this->data['page'] = $page;

        // parameters to template
        return $this->render( 'AppBundle:Page:index.html.twig', $this->data );
    }

}
