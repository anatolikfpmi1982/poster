<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

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
     */
    public function showAction( $id ) {
        $em      = $this->get( 'doctrine.orm.entity_manager' );
        $picture = $em->getRepository( 'AppBundle:Picture' )->find( $id );

        $this->blocks = [ 'CategoryMenu' => 1, 'Popular' => 2, 'MainMenu' => 3 ];
        $this->doBlocks();

        $this->get( 'app.session_manager' )->addLastVisitedItem( $picture->getId() );

        $this->data['picture']     = $picture;
        $this->data['pictureSize'] = $em->getRepository( 'AppBundle:PictureSize' )->findBy( [ 'isActive' => true ], [ 'width' => 'ASC' ] );
        $this->data['materials'] = $em->getRepository( 'AppBundle:BannerMaterial' )->findBy( [ 'isActive' => true ], [ 'id' => 'ASC' ] );

        // parameters to template
        return $this->render( 'AppBundle:Pictures:show.html.twig', $this->data );
    }

}
