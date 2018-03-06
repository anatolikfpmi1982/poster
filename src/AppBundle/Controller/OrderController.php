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
        $this->menu = '/order';
        $this->pageSlug = '';
        $this->pageType = 'order';
        $this->doBlocks();

        $form = $this->getOrderForm();

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
                    $cart[$k]['picture'] = $this->em->getRepository('AppBundle:Picture')->findOneBy(['isActive' => true, 'id' => $v['picture_id']]);
                } else {
                    $cart[$k]['own_picture'] = $this->em->getRepository('AppBundle:OwnPicture')->findOneBy(['id' => $v['own_picture_id']]);
                }
                $totalPrice += (float)$v['price'];
            }
        } else {
            $cart = [];
        }


        $this->data['cart'] = $cart;
        $this->data['form'] = $form->createView();
        $this->data['total_price'] = $totalPrice;

        // parameters to template
        return $this->render('AppBundle:Cart:cart.html.twig', $this->data);
    }

    /**
     * @Route("/order/done", name="order_done")
     *
     * @return Response
     */
    public function doneAction() {
        $this->menu = '/order';
        $this->pageSlug = '';
        $this->pageType = 'order';
        $this->doBlocks();

        return $this->render('AppBundle:Cart:done.html.twig', $this->data);
    }

    /**
     * @param $form
     */
    private function saveForm($form) {
        $em = $this->get('doctrine.orm.entity_manager');
        $data = $form->getData();

        $cart = $this->get( 'app.session_manager' )->getCart();
        if($cart) {
            $orderId = $em->getRepository('AppBundle:Order')->getLastOrderId();
            foreach ($cart as $v) {
                $sizes = explode('x', $v['sizes']);
                $record = new Order();
                $record->setGroupId($orderId + 1)
                    ->setFullname($data['fullname'])
                    ->setEmail($data['email'])
                    ->setPhone($data['phone'])
                    ->setCity($data['city'])
                    ->setAddress($data['address'])
                    ->setCompany($data['company'])
                    ->setComment($data['comment'])
                    ->setCreatedAt(new \DateTime())
                    ->setUpdatedAt(new \DateTime())
                    ->setHeight($sizes[0])
                    ->setWidth($sizes[1])
                    ->setPrice($v['price'])
                    ->setType($v['type_id']);

                if($v['own_picture_id']) {
                    $picture = $em->getRepository('AppBundle:OwnPicture')->findOneBy(['id' => $v['own_picture_id']]);
                    $record->setOwnPicture($picture);
                } else {
                    $picture = $em->getRepository('AppBundle:Picture')->findOneBy(['id' => $v['picture_id']]);
                    $record->setPicture($picture);
                }

                switch($v['type_id']){
                    case 'banner':
                        $bannerMaterial = $em->getRepository('AppBundle:BannerMaterial')->findOneBy(['id' => $v['banner_material_id']]);
                        $record->setBannerMaterial($bannerMaterial);

                        $underframe = $em->getRepository('AppBundle:Underframe')->findOneBy(['id' => $v['underframe_id']]);
                        $record->setUnderframe($underframe);
                        break;
                    case 'frame':
                        $frame = $em->getRepository('AppBundle:Frame')->findOneBy(['id' => $v['frame_id']]);
                        $record->setFrame($frame);

                        $frameMaterial = $em->getRepository('AppBundle:FrameMaterial')->findOneBy(['id' => $v['frame_material_id']]);
                        $record->setFrameMaterial($frameMaterial);

                        $material = $em->getRepository('AppBundle:Material')->findOneBy(['name' => $v['frame_material']]);
                        $record->setMaterial($material);

                        $frameColor = $em->getRepository('AppBundle:FrameColor')->findOneBy(['title' => $v['frame_color']]);
                        $record->setFrameColor($frameColor);
                        break;
                    case 'module':
                        $bannerMaterial = $em->getRepository('AppBundle:BannerMaterial')->findOneBy(['id' => $v['banner_material_id']]);
                        $record->setBannerMaterial($bannerMaterial);

                        $underframe = $em->getRepository('AppBundle:Underframe')->findOneBy(['id' => $v['underframe_id']]);
                        $record->setUnderframe($underframe);

                        $moduleType = $em->getRepository('AppBundle:ModuleType')->findOneBy(['id' => $v['module_type_id']]);
                        $record->setModuleType($moduleType);
                        break;
                }

                $em->persist($record);
            }
            $em->flush();

            $settings = $this->getSiteSettings();


            if(!empty($settings['from_email']) && !empty($settings['to_email'])) {
                $message = (new \Swift_Message('Новый заказ'))
                    ->setFrom($settings['from_email'])
                    ->setTo($settings['to_email'])
                    ->setBody(
                        $this->renderView(
                            'AppBundle:Emails:order.html.twig',
                            array('orderId' => $orderId, 'count' => count($cart))
                        ),
                        'text/html'
                    );

                $this->get('mailer')->send($message);
            }
        }

        $this->get( 'app.session_manager' )->cleanCart();
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function getOrderForm () {
        return $this->createFormBuilder()
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
    }
}
