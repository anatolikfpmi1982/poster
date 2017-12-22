<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CategoriesController
 */
class CategoriesController extends FrontController
{
    const PAGE_LIMIT = 16;

    /**
     * @param string $slug
     * @param Request $request
     *
     * @Route("/category/{slug}", name="category")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($slug, Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $category = $em->getRepository('AppBundle:Category3')->findOneBySlug($slug);
        $category->count = count($category->getPictures());

        $queryBuilder = $em->getRepository('AppBundle:Picture')->getActivePicturesFromCategory($category->getId());
        $query = $queryBuilder->getQuery();


//        echo $query->getSQL();die();
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            self::PAGE_LIMIT/*limit per page*/
        );

        $this->blocks = [ 'CategoryMenu' => 1, 'Reviews' => 2 , 'MainMenu' => 3, 'BreadCrumb' => 4, 'LastVisited' => 5 ];
        $this->menu = '/';
        $this->pageSlug = $slug;
        $this->pageType = 'category';
        $this->doBlocks();
        $this->data['pagination'] = $pagination;
        $category->setDescription($this->get('helper.textformater')->formatMoreText($category->getDescription()));
        $this->data['category'] = $category;
        $this->data['filters']['tpls'] = $em->getRepository('AppBundle:PictureForm')->findBy(['isActive' => true]);

        // parameters to template
        return $this->render('AppBundle:Categories:show.html.twig', $this->data);
    }

}
