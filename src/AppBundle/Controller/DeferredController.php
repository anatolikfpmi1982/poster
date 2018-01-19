<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class DeferredController
 */
class DeferredController extends FrontController {
    const PAGE_LIMIT = 16;

    /**
     * @Route("/deferred", name="deferred")
     *
     * @return Response
     * @throws BadRequestHttpException
     * @throws \LogicException
     */
    public function showAction(Request $request) {
        $this->menu = '/deferred';
        $this->pageSlug = '';
        $this->pageType = 'deferred';
        $this->doBlocks();

        $ids = $this->get( 'app.session_manager' )->getDeferredItems();
        $queryBuilder = $this->em->getRepository('AppBundle:Picture')->getActivePicturesForDeferred($ids);
        $query = $queryBuilder->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            self::PAGE_LIMIT/*limit per page*/
        );
        $this->data['pagination'] = $pagination;

        // parameters to template
        return $this->render('AppBundle:Deferred:show.html.twig', $this->data);
    }

    /**
     * @Route("/ajax/picture/defer/add", name="picture_defer_add")
     *
     * @param Request $request
     *
     * @throws \InvalidArgumentException
     *
     * @return Response
     */
    public function addDeferAction(Request $request) {
        $id = $request->query->get('id');

        $this->get( 'app.session_manager' )->addDeferredItem( (int)$id );
        $count = $this->get( 'app.session_manager' )->getDeferredCount();
        // parameters to template
        return new JsonResponse(['result' => 1, 'count' => $count]);
    }

    /**
     * @Route("/ajax/picture/defer/delete", name="picture_defer_delete")
     *
     * @param Request $request
     *
     * @throws \InvalidArgumentException
     *
     * @return Response
     */
    public function deleteDeferAction(Request $request) {
        $id = (int)$request->query->get('id');
        $this->get( 'app.session_manager' )->deleteDeferredItem( $id );

        // parameters to template
        return new JsonResponse(array('result' => 'success'));
    }

    /**
     * @Route("/ajax/picture/defer/count", name="picture_defer_count")
     *
     * @return Response
     */
    public function deferCountAction() {
        $count = $this->get( 'app.session_manager' )->getDeferredCount();
        // parameters to template
        return new JsonResponse(array('result' => 'success', 'count' => $count));
    }

}
