<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Frame;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FramesController
 */
class FramesController extends FrontController
{
    const PAGE_LIMIT = 5;

    /**
     * @Route("/frames", name="frames")
     *
     * @throws \LogicException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $this->blocks = array_merge($this->blocks, ['LastVisited' => 6]);
        $this->menu = '/frames';
        $this->pageSlug = '';
        $this->pageType = 'frames';
        $this->doBlocks();

        $paginator  = $this->get('knp_paginator');
        $query = $this->em->getRepository('AppBundle:Frame')->getFrameList();
        $this->data['pagination'] = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            self::PAGE_LIMIT/*limit per page*/
        );

        // parameters to template
        return $this->render('AppBundle:Frames:list.html.twig', $this->data);
    }

    /**
     *
     * @Route("/frame/{id}", name="frame")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        $this->blocks = array_merge($this->blocks, ['LastVisited' => 6]);
        $this->menu = '/frame/'. (int)$id;
        $this->pageSlug = $id;
        $this->pageType = 'frame';
        $this->doBlocks();

        $frame = $this->em->getRepository('AppBundle:Frame')->find($id);
        if($frame instanceof Frame) {
            $frame->setDescription( $this->get( 'helper.textformater' )->formatMoreText( $frame->getDescription() ) );
        }
        $this->data['frame'] = $frame;

        // parameters to template
        return $this->render('AppBundle:Frames:show.html.twig', $this->data);
    }


}
