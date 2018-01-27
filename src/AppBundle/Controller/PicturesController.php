<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Image;
use AppBundle\Entity\Picture;
use AppBundle\Entity\Settings;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class PicturesController
 */
class PicturesController extends FrontController {
    /**
     * @param int $id
     * @param Request $request
     *
     * @Route("/picture/{id}", name="picture")
     *
     * @return Response
     * @throws BadRequestHttpException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function showAction( $id, Request $request ) {
        $this->blocks = array_merge($this->blocks, ['LastVisited' => 6, 'Similar' => 7]);
        $this->pageSlug = $id;
        $this->pageType = 'picture';
        $this->menu = '/picture';
        $this->id = $id;
        $this->doBlocks();

        /** @var Picture $picture */
        $picture = $this->em->getRepository( 'AppBundle:Picture' )->find( $id );
        if(!$picture instanceof Picture) {
            throw new BadRequestHttpException('Картина не найдена.');
        } else {
            $picture->setPopularity($picture->getPopularity() + 1);
            $this->em->persist($picture);
            $this->em->flush();
        }

        $cartItem = [];
        if($request->get('cart_id')) {
            $cartItem = $this->get( 'app.session_manager' )->getFromCart($request->get('cart_id'));
        }
        $this->get( 'app.session_manager' )->addLastVisitedItem( $picture->getId() );
        $this->data['pictureMain'] = $picture;
        if($picture->getImage() instanceof Image) {
            $imgFile = $picture->getImage()->getBaseFile();
            $size = getimagesize($imgFile);
            $this->data['pictureBaseWidth'] = $size[0];
            $this->data['pictureBaseHeight'] = $size[1];
            $imgFileSmall = $picture->getImage()->getSmallThumbBaseFile();
            $size = getimagesize($imgFileSmall);
            $this->data['pictureSmallWidth'] = $size[0];
            $this->data['pictureSmallHeight'] = $size[1];
            $imgFileThumb = $picture->getImage()->getThumbBaseFile();
            $size = getimagesize($imgFileThumb);
            $this->data['pictureThumbWidth'] = $size[0];
            $this->data['pictureThumbHeight'] = $size[1];
        }
        $this->data['pictureSize'] = $this->em->getRepository( 'AppBundle:PictureSize' )->findBy( [ 'isActive' => true ], [ 'width' => 'ASC' ] );
        $this->data['materials']   = $this->em->getRepository( 'AppBundle:BannerMaterial' )->findBy( [ 'isActive' => true ], [ 'id' => 'ASC' ] );
        $this->data['pictureMaterials']   = $this->em->getRepository( 'AppBundle:FrameMaterial' )->findBy( [ 'isActive' => true ], [ 'id' => 'ASC' ] );
        $this->data['moduleTypes'] = $this->em->getRepository( 'AppBundle:ModuleType' )->findBy( [ 'isActive' => true ], [ 'id' => 'ASC' ] );
        $this->data['thicknesses'] = $this->em->getRepository( 'AppBundle:Underframe' )->findBy( [ 'isActive' => true ], [ 'id' => 'ASC' ] );
        $this->data['pictureThicknesses'] = $this->em->getRepository( 'AppBundle:Frame' )->findBy( [ 'isActive' => true ], [ 'id' => 'ASC' ] );
        $this->data['mats'] = $this->em->getRepository( 'AppBundle:Mat' )->findBy( [ 'isActive' => true ], [ 'id' => 'ASC' ] );
        $_frameSettings = $this->em->getRepository( 'AppBundle:Settings' )->findOneByName('frame_settings');
        $frameSettings = [];
        if($_frameSettings instanceof Settings) {
            $frameSettings = unserialize($_frameSettings->getSettings());
        }
        $this->data['frameSettings'] = $frameSettings;
        $this->data['cart_item'] = $cartItem;
        $this->data['cart_id'] = $request->get('cart_id');

        // parameters to template
        return $this->render( 'AppBundle:Pictures:show.html.twig', $this->data );
    }

    /**
     * @param int $id
     * @param Request $request
     *
     * @Route("/picture/{id}/module", name="picture_module")
     *
     * @return Response
     * @throws BadRequestHttpException
     */
    public function showModuleAction( $id, Request $request ) {
        $this->blocks = array_merge($this->blocks);
        $this->pageSlug = $id;
        $this->pageType = 'picture';
        $this->menu = '/picture';
        $this->id = $id;
        $this->doBlocks();

        /** @var Picture $picture */
        $picture = $this->em->getRepository( 'AppBundle:Picture' )->find( $id );
        if(!$picture instanceof Picture) {
            throw new BadRequestHttpException('Картина не найдена.');
        }

        $this->data['pictureMain'] = $picture;
        if($picture->getImage() instanceof Image) {
            $imgFile = $picture->getImage()->getBaseFile();
            $size = getimagesize($imgFile);
            $this->data['pictureBaseWidth'] = $size[0];
            $this->data['pictureBaseHeight'] = $size[1];
            $imgFileSmall = $picture->getImage()->getSmallThumbBaseFile();
            $size = getimagesize($imgFileSmall);
            $this->data['pictureSmallWidth'] = $size[0];
            $this->data['pictureSmallHeight'] = $size[1];
            $imgFileThumb = $picture->getImage()->getThumbBaseFile();
            $size = getimagesize($imgFileThumb);
            $this->data['pictureThumbWidth'] = $size[0];
            $this->data['pictureThumbHeight'] = $size[1];
        }
        $this->data['moduleTypes'] = $this->em->getRepository( 'AppBundle:ModuleType' )->findBy( [ 'isActive' => true ], [ 'id' => 'ASC' ] );

        // parameters to template
        return $this->render( 'AppBundle:Pictures:show.module.html.twig', $this->data );
    }

    /**
     * @param int $id
     * @param Request $request
     *
     * @Route("/picture/{id}/frame", name="picture_frame")
     *
     * @return Response
     * @throws BadRequestHttpException
     */
    public function showFrameAction( $id, Request $request ) {
        $this->blocks = array_merge($this->blocks);
        $this->pageSlug = $id;
        $this->pageType = 'picture';
        $this->menu = '/picture/' . $id;
        $this->id = $id;
        $this->doBlocks();

        /** @var Picture $picture */
        $picture = $this->em->getRepository( 'AppBundle:Picture' )->find( $id );
        if(!$picture instanceof Picture) {
            throw new BadRequestHttpException('Картина не найдена.');
        }

        $this->data['pictureMain'] = $picture;
        if($picture->getImage() instanceof Image) {
            $imgFile = $picture->getImage()->getBaseFile();
            $size = getimagesize($imgFile);
            $this->data['pictureBaseWidth'] = $size[0];
            $this->data['pictureBaseHeight'] = $size[1];
            $imgFileSmall = $picture->getImage()->getSmallThumbBaseFile();
            $size = getimagesize($imgFileSmall);
            $this->data['pictureSmallWidth'] = $size[0];
            $this->data['pictureSmallHeight'] = $size[1];
            $imgFileThumb = $picture->getImage()->getThumbBaseFile();
            $size = getimagesize($imgFileThumb);
            $this->data['pictureThumbWidth'] = $size[0];
            $this->data['pictureThumbHeight'] = $size[1];
        }
        $this->data['pictureThicknesses'] = $this->em->getRepository( 'AppBundle:Frame' )->findBy( [ 'isActive' => true ], [ 'id' => 'ASC' ] );

        // parameters to template
        return $this->render( 'AppBundle:Pictures:show.frame.html.twig', $this->data );
    }
}
