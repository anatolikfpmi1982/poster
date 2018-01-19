<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use AppBundle\Entity\OwnPicture;
use AppBundle\Entity\Settings;
use AppBundle\Entity\Image;

/**
 * Class DeferredController
 */
class MyFilesController extends FrontController {
    /**
     * @param integer $id
     * @param Request $request
     *
     * @Route("/myfile/{id}", name="my_file_page")
     *
     * @return Response
     * @throws BadRequestHttpException
     */
    public function showAction( $id, Request $request )
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $pictureId = $this->get('app.session_manager')->getMyFilesItem($id);
        $picture = null;

        if ($pictureId) {
            /** @var OwnPicture $picture */
            $picture = $em->getRepository('AppBundle:OwnPicture')->find($pictureId);
        }
        if (!$picture instanceof OwnPicture) {
            throw new BadRequestHttpException('Картина не найдена.');
        }

        $this->pageSlug = $pictureId;
        $this->pageType = 'picture';
        $this->id = $pictureId;
        $this->doBlocks();

        $this->get('app.session_manager')->addLastVisitedItem($picture->getId());

        $this->data['pictureMain'] = $picture;
        if ($picture->getImage() instanceof Image) {
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
        $this->data['pictureSize'] = $em->getRepository('AppBundle:PictureSize')->findBy(['isActive' => true], ['width' => 'ASC']);
        $this->data['materials'] = $em->getRepository('AppBundle:BannerMaterial')->findBy(['isActive' => true], ['id' => 'ASC']);
        $this->data['pictureMaterials'] = $em->getRepository('AppBundle:FrameMaterial')->findBy(['isActive' => true], ['id' => 'ASC']);
        $this->data['moduleTypes'] = $em->getRepository('AppBundle:ModuleType')->findBy(['isActive' => true], ['id' => 'ASC']);
        $this->data['thicknesses'] = $em->getRepository('AppBundle:Underframe')->findBy(['isActive' => true], ['id' => 'ASC']);
        $this->data['pictureThicknesses'] = $em->getRepository('AppBundle:Frame')->findBy(['isActive' => true], ['id' => 'ASC']);
        $this->data['mats'] = $em->getRepository('AppBundle:Mat')->findBy(['isActive' => true], ['id' => 'ASC']);
        $_frameSettings = $em->getRepository('AppBundle:Settings')->findOneByName('frame_settings');
        $frameSettings = [];
        if ($_frameSettings instanceof Settings) {
            $frameSettings = unserialize($_frameSettings->getSettings());
        }
        $this->data['frameSettings'] = $frameSettings;
        $this->data['ownPicture'] = true;

        // parameters to template
        return $this->render('AppBundle:Pictures:upload.html.twig', $this->data);
    }

    const PAGE_LIMIT = 5;

    /**
     * @Route("/myfiles", name="my_files")
     *
     * @return Response
     * @throws BadRequestHttpException
     */
    public function listAction(Request $request) {
        $em = $this->get('doctrine.orm.entity_manager');

        $ids = $this->get( 'app.session_manager' )->getMyFiles();

        $queryBuilder = $em->getRepository('AppBundle:OwnPicture')->getOwnPicturesForMyFiles($ids);
        $query = $queryBuilder->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            self::PAGE_LIMIT/*limit per page*/
        );

        $this->menu = '/';
        $this->pageSlug = '';
        $this->pageType = 'my_files';
        $this->doBlocks();
        $this->data['pagination'] = $pagination;

        // parameters to template
        return $this->render('AppBundle:MyFiles:list.html.twig', $this->data);
    }

    /**
     * @param Request $request
     *
     * @Route("/ajax/picture/upload", name="picture_upload")
     *
     * @return JsonResponse
     */
    public function uploadFileAction( Request $request ) {
        $em = $this->get( 'doctrine.orm.entity_manager' );

        $uploadedFile = $request->files->all()[0];

        $picture = new OwnPicture();

        $picture->setName($uploadedFile->getClientOriginalName());
        $picture->setCreatedAt(new \DateTime());
        $picture->setUpdatedAt(new \DateTime());

        $image = new Image();
        $image->setFile($uploadedFile);
        $image->upload();
        $image->setCreatedAt(new \DateTime())
            ->setEntityName($picture::IMAGE_PATH);
        $em->persist($image);
        $picture->setImage($image);

        $em->persist($picture);
        $em->flush();

        $this->get( 'app.session_manager' )->addMyFile($picture->getId());

        // parameters to template
        return new JsonResponse(['result' => 'success']);
    }

    /**
     * @Route("/ajax/myfiles/delete", name="my_files_delete")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function deleteFromMyFilesAction(Request $request) {
        $id = $request->query->get('id');

        $this->get( 'app.session_manager' )->deleteFromMyFiles( (int)$id );

        // parameters to template
        return new JsonResponse(array('result' => 'success'));
    }

    /**
     * @Route("/ajax/myfiles/count", name="myfiles_count")
     *
     * @return Response
     */
    public function countAction() {
        $count = $this->get( 'app.session_manager' )->getMyFilesCount();

        // parameters to template
        return new JsonResponse(array('result' => 'success', 'count' => $count));
    }
}
