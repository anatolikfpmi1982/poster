<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Image;
use AppBundle\Entity\Picture;
use AppBundle\Entity\Settings;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class PicturesController
 */
class PicturesController extends FrontController {
    /**
     * @param int $id
     *
     * @Route("/picture/{id}", name="picture")
     *
     * @return Response
     * @throws BadRequestHttpException
     */
    public function showAction( $id ) {
        $em      = $this->get( 'doctrine.orm.entity_manager' );
        /** @var Picture $picture */
        $picture = $em->getRepository( 'AppBundle:Picture' )->find( $id );
        if(!$picture instanceof Picture) {
            throw new BadRequestHttpException('Картина не найдена.');
        }

        $this->pageSlug = $id;
        $this->pageType = 'picture';
        $this->id = $id;
        $this->doBlocks();

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
        $this->data['pictureSize'] = $em->getRepository( 'AppBundle:PictureSize' )->findBy( [ 'isActive' => true ], [ 'width' => 'ASC' ] );
        $this->data['materials']   = $em->getRepository( 'AppBundle:BannerMaterial' )->findBy( [ 'isActive' => true ], [ 'id' => 'ASC' ] );
        $this->data['pictureMaterials']   = $em->getRepository( 'AppBundle:FrameMaterial' )->findBy( [ 'isActive' => true ], [ 'id' => 'ASC' ] );
        $this->data['moduleTypes'] = $em->getRepository( 'AppBundle:ModuleType' )->findBy( [ 'isActive' => true ], [ 'id' => 'ASC' ] );
        $this->data['thicknesses'] = $em->getRepository( 'AppBundle:Underframe' )->findBy( [ 'isActive' => true ], [ 'id' => 'ASC' ] );
        $this->data['pictureThicknesses'] = $em->getRepository( 'AppBundle:Frame' )->findBy( [ 'isActive' => true ], [ 'id' => 'ASC' ] );
        $this->data['mats'] = $em->getRepository( 'AppBundle:Mat' )->findBy( [ 'isActive' => true ], [ 'id' => 'ASC' ] );
        $_frameSettings = $em->getRepository( 'AppBundle:Settings' )->findOneByName('frame_settings');
        $frameSettings = [];
        if($_frameSettings instanceof Settings) {
            $frameSettings = unserialize($_frameSettings->getSettings());
        }
        $this->data['frameSettings'] = $frameSettings;

        // parameters to template
        return $this->render( 'AppBundle:Pictures:show.html.twig', $this->data );
    }
}
