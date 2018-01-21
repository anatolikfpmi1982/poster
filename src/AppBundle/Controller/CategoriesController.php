<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category3;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CategoriesController
 */
class CategoriesController extends FrontController
{
    const PAGE_LIMIT = 20;

    /**
     * @param string $slug
     * @param Request $request
     *
     * @Route("/category/{slug}", name="category")
     *
     * @throws \LogicException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($slug, Request $request)
    {
        $this->blocks = array_merge($this->blocks, ['LastVisited' => 6, 'Deferred' => 7]);
        $this->menu = '/';
        $this->pageSlug = $slug;
        $this->pageType = 'category';
        $this->doBlocks();


        /** @var Category3 $category */
        $category = $this->em->getRepository('AppBundle:Category3')->findOneBySlug($slug);
        $category->count = count($category->getPictures());

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->em->getRepository('AppBundle:Picture')->getActivePicturesFromCategory($category);
        $query = $queryBuilder->getQuery();

        //add multiple popularity
        if($request->query->get('sort') == 'p.popularity') {
            $_GET['sort'] = 'p.isTop+p.popularity';
        }

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            self::PAGE_LIMIT/*limit per page*/,
            ['wrap-queries' => true, 'defaultSortFieldName' => 'cp.weight', 'defaultSortDirection' => 'desc']
        );


        $this->data['pagination'] = $pagination;
        $category->setDescription($this->get('helper.textformater')->formatMoreText($category->getDescription()));
        $this->data['category'] = $category;
        $this->data['deferredItems'] = $this->get( 'app.session_manager' )->getDeferredItems();
        $this->data['mainCategoryId'] = $category->getId();
        $this->data['filters']['tpls'] = $this->em->getRepository('AppBundle:PictureForm')->findBy(['isActive' => true]);

        // parameters to template
        return $this->render('AppBundle:Categories:show.html.twig', $this->data);
    }

}
