<?php
namespace AppBundle\Controller;

use Sonata\CoreBundle\Form\Type\BooleanType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sonata\AdminBundle\Controller\CoreController;

/**
 * Class AdminController
 */
class AdminController extends CoreController
{
    public function settingsAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('phone', TextType::class, ['label' => 'Телефон'])
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('contacts', TextareaType::class, ['label' => 'Контактные данные'])
            ->add('title', TextType::class, ['label' => 'Заголовок'])
            ->add('logo', FileType::class, ['label' => 'Логотип'])
            ->add('under_slider_text', TextAreaType::class, ['label' => 'Текст под слайдером'])
            ->add('favicon', FileType::class, ['label' => 'Favicon'])
            ->add('metrics_yandex', TextAreaType::class, ['label' => 'Метрики Яндекс'])
            ->add('metrics_google', TextAreaType::class, ['label' => 'Метрики Google'])
            ->add('seo_keywords', TextAreaType::class, ['label' => 'SEO - Keywords'])
            ->add('seo_description', TextAreaType::class, ['label' => 'SEO - Description'])
            ->add('seo_title', TextType::class, ['label' => 'SEO - Title'])
            ->add('enable_call_back', BooleanType::class, ['label' => 'Использовать функцию "Заказать звонок"'])
            ->add('enable_sms', BooleanType::class, ['label' => 'Отправлять SMS после заказа звонка'])
            ->add('save', SubmitType::class, array('label' => 'Сохранить'))
            ->getForm();

        return $this->render('AppBundle:Admin:settings.html.twig', array(
            'base_template'   => $this->getBaseTemplate(),
            'admin_pool'      => $this->container->get('sonata.admin.pool'),
            'blocks'          => $this->container->getParameter('sonata.admin.configuration.dashboard_blocks'),
            'form'            => $form->createView(),
        ));
    }

}