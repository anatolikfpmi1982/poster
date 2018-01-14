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
            ->add('address2', TextType::class, ['label' => 'Адрес 2', 'required' => false])
            ->add('company', TextType::class, ['label' => 'Компания', 'required' => false])
            ->add('fax', TextType::class, ['label' => 'Факс', 'required' => false])
            ->add('comment', TextareaType::class, ['label' => 'Комментарий к заказу', 'required' => false])
            ->add('conditions', CheckboxType::class, ['label' => 'Я соглашаюсь с данными условиями работы *', 'required' => true])
            ->add('personal_data', CheckboxType::class, ['label' => 'Согласен на обработку моих персональных данных *', 'required' => true])
            ->add('save', SubmitType::class, array('label' => 'Заказать'))
            ->getForm();

        if($request->getMethod() === 'POST') {
            $form->handleRequest( $request );

            if ( $form->isValid() ) {
                $this->saveForm( $form );
            } else {
                throw new BadRequestHttpException($form->getErrors());
            }
        }

        /** @var array $cart */
        $totalPrice = 0;
        $cart = $this->get( 'app.session_manager' )->getCart();
        if( $cart) {
            foreach($cart as $k => $v) {
                $cart[$k]['picture'] = $em->getRepository('AppBundle:Picture')->findOneBy(['isActive' => true, 'id' => $v['picture_id']]);
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

        $record = new Order();
        $record->setFullname($data['fullname']);
        $record->setEmail($data['email']);
        $record->setPhone($data['phone']);
        $record->setCity($data['city']);
        $record->setAddress($data['address']);
        $record->setAddress2($data['address2']);
        $record->setCompany($data['company']);
        $record->setFax($data['fax']);
        $record->setComment($data['comment']);
        $record->setCreatedAt(new \DateTime());
        $record->setUpdatedAt(new \DateTime());
        $record->setIsActive(false);

        $record->setHeight(22);
        $record->setWidth(22);
        $record->setPrice(222);

        $em->persist($record);
        $em->flush();

        $this->get( 'app.session_manager' )->cleanCart();
    }
}
