<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class OrdersController
 */
class OrderController extends FrontController {
    /**
     * @Route("/order", name="order")
     *
     * @return Response
     * @throws BadRequestHttpException
     */
    public function showAction(Request $request) {
        $em = $this->get('doctrine.orm.entity_manager');

        $form = $this->createFormBuilder()
            ->add('fullname', TextType::class, ['label' => 'Ф.И.О. *', 'required' => true])
            ->add('email', EmailType::class, ['label' => 'Email *', 'required' => true])
            ->add('phone', TextType::class, ['label' => 'Телефон *', 'required' => true])
            ->add('city', TextType::class, ['label' => 'Город *', 'required' => true])
            ->add('address', TextType::class, ['label' => 'Адрес *', 'required' => true])
            ->add('company', TextType::class, ['label' => 'Компания', 'required' => false])
            ->add('comment', TextareaType::class, ['label' => 'Комментарий к заказу', 'required' => false])
            ->add('conditions', CheckboxType::class, ['label' => 'Я соглашаюсь с данными условиями работы *', 'required' => true])
            ->add('save', SubmitType::class, array('label' => 'Заказать'))
            ->getForm();

        if($request->getMethod() === 'POST') {
            $form->handleRequest( $request );

            if ( $form->isValid() ) {
                $this->saveForm( $form );

                return $this->redirect($this->generateUrl('order_done'));
            } else {
                throw new BadRequestHttpException($form->getErrors());
            }
        }

        /** @var array $cart */
        $totalPrice = 0;
        $cart = $this->get( 'app.session_manager' )->getCart();
        if( $cart) {
            foreach($cart as $k => $v) {
                if(!empty($v['picture_id'])) {
                    $cart[$k]['picture'] = $em->getRepository('AppBundle:Picture')->findOneBy(['isActive' => true, 'id' => $v['picture_id']]);
                } else {
                    $cart[$k]['own_picture'] = $em->getRepository('AppBundle:OwnPicture')->findOneBy(['id' => $v['own_picture_id']]);
                }
                $totalPrice += (float)$v['price'];
            }
        } else {
            $cart = [];
        }

        $this->menu = '/order';
        $this->pageSlug = '';
        $this->pageType = 'order';
        $this->doBlocks();
        $this->data['cart'] = $cart;
        $this->data['form'] = $form->createView();
        $this->data['total_price'] = $totalPrice;

        // parameters to template
        return $this->render('AppBundle:Cart:cart.html.twig', $this->data);
    }

    private function saveForm($form) {
        $em = $this->get('doctrine.orm.entity_manager');
        $data = $form->getData();

        $cart = $this->get( 'app.session_manager' )->getCart();
        if(!empty($cart)) {
            $orderId = $em->getRepository('AppBundle:Order')->getLastOrderId();
            foreach ($cart as $v) {
                $record = new Order();
                $record->setGroupId($orderId + 1);
                $record->setFullname($data['fullname']);
                $record->setEmail($data['email']);
                $record->setPhone($data['phone']);
                $record->setCity($data['city']);
                $record->setAddress($data['address']);
                $record->setCompany($data['company']);
                $record->setComment($data['comment']);
                $record->setCreatedAt(new \DateTime());
                $record->setUpdatedAt(new \DateTime());

                $sizes = explode('x', $v['sizes']);
                $record->setHeight($sizes[0]);
                $record->setWidth($sizes[1]);
                $record->setPrice($v['price']);
                $record->setType($v['type_id']);

                if($v['own_picture_id']) {
                    $picture = $em->getRepository('AppBundle:OwnPicture')->findOneBy(['id' => $v['own_picture_id']]);
                    $record->setOwnPicture($picture);
                } else {
                    $picture = $em->getRepository('AppBundle:Picture')->findOneBy(['id' => $v['picture_id']]);
                    $record->setPicture($picture);
                }

                if($v['type_id'] == 'banner') {
                    $bannerMaterial = $em->getRepository('AppBundle:BannerMaterial')->findOneBy(['id' => $v['banner_material_id']]);
                    $record->setBannerMaterial($bannerMaterial);

                    $underframe = $em->getRepository('AppBundle:Underframe')->findOneBy(['id' => $v['underframe_id']]);
                    $record->setUnderframe($underframe);
                } elseif($v['type_id'] == 'frame') {
                    $frame = $em->getRepository('AppBundle:Frame')->findOneBy(['id' => $v['frame_id']]);
                    $record->setFrame($frame);

                    $frameMaterial = $em->getRepository('AppBundle:FrameMaterial')->findOneBy(['id' => $v['frame_material_id']]);
                    $record->setFrameMaterial($frameMaterial);
                } elseif($v['type_id'] == 'module') {
                    $bannerMaterial = $em->getRepository('AppBundle:BannerMaterial')->findOneBy(['id' => $v['banner_material_id']]);
                    $record->setBannerMaterial($bannerMaterial);

                    $underframe = $em->getRepository('AppBundle:Underframe')->findOneBy(['id' => $v['underframe_id']]);
                    $record->setUnderframe($underframe);

                    $moduleType = $em->getRepository('AppBundle:ModuleType')->findOneBy(['id' => $v['module_type_id']]);
                    $record->setModuleType($moduleType);
                }

                $em->persist($record);
            }
            $em->flush();
        }


        $this->get( 'app.session_manager' )->cleanCart();
    }

    /**
     * @Route("/order/done", name="order_done")
     *
     * @return Response
     */
    function doneAction() {
        $this->menu = '/order';
        $this->pageSlug = '';
        $this->pageType = 'order';
        $this->doBlocks();

        return $this->render('AppBundle:Cart:done.html.twig', $this->data);
    }
}
