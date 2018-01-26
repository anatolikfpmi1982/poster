<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AuthorsController
 */
class AuthorsController extends FrontController
{
    const PAGE_LIMIT = 20;

    /**
     * @param string $slug
     * @param Request $request
     *
     * @Route("/author/{slug}", name="author")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($slug, Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $author = $em->getRepository('AppBundle:Author')->findOneBySlug($slug);

        $queryBuilder = $em->getRepository('AppBundle:Picture')->getActivePicturesByAuthor($author->getSlug());
        $query = $queryBuilder->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            self::PAGE_LIMIT/*limit per page*/
        );

        $this->blocks['LastVisited'] = 6;
        $this->menu = '/';
        $this->pageSlug = $slug;
        $this->pageType = 'author';
        $this->doBlocks();
        $this->data['pagination'] = $pagination;
        $this->data['author'] = $author;
        $this->data['filters']['tpls'] = $em->getRepository('AppBundle:PictureForm')->findBy(['isActive' => true]);

        // parameters to template
        return $this->render('AppBundle:Authors:show.html.twig', $this->data);
    }

}
