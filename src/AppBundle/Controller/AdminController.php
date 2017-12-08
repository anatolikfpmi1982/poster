<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Settings;
use Doctrine\ORM\EntityManager;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Controller\CoreController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminController
 */
class AdminController extends CoreController
{
    const FOLDER = __DIR__.'/../../../web/';

    /**
     * @var EntityManager
     */
    private $em;

    private $record = null;

    private $logo = null;

    public function settingsAction(Request $request)
    {
        $this->em = $this->container->get('doctrine.orm.entity_manager');
        $this->record = $this->em->getRepository('AppBundle:Settings')->findOneByName('site_settings');
        if($this->record) {
            $data = unserialize($this->record->getSettings());
            if(!empty($data['logo'])) {
                $this->logo = $data['logo'];
                unset($data['logo']);
            }
        } else {
            $data = [
                'phone' => '',
                'email' => '',
                'title' => '',
                'under_slider_text' => '',
                'logo_header_text' => '',
                'logo_footer_text' => '',
                'contacts' => '',
                'metrics_yandex' => '',
                'metrics_google' => '',
                'fb_buttons' => '',
                'vk_buttons' => '',
                'seo_keywords' => '',
                'seo_description' => '',
                'seo_title' => '',
                'enable_call_back' => false,
                'enable_sms' => false
            ];
        }

        $form = $this->createFormBuilder()
            ->add('phone', TextType::class, ['label' => 'Телефон', 'required' => false])
            ->add('email', EmailType::class, ['label' => 'Email', 'required' => false])
            ->add('title', TextType::class, ['label' => 'Заголовок', 'required' => false])
            ->add('under_slider_text', TextAreaType::class, ['label' => 'Текст под слайдером', 'required' => false])
            ->add('logo_header_text', TextareaType::class, ['label' => 'Текст под лого (хедер)', 'required' => false])
            ->add('logo_footer_text', TextareaType::class, ['label' => 'Текст под лого (футер)', 'required' => false])
            ->add('contacts', CKEditorType::class, ['label' => 'Контактные данные (футер)', 'required' => false])
            ->add('logo', FileType::class, ['label' => 'Логотип', 'required' => false])
            ->add('favicon', FileType::class, ['label' => 'Favicon', 'required' => false])
            ->add('metrics_yandex', TextAreaType::class, ['label' => 'Метрики Яндекс', 'required' => false])
            ->add('metrics_google', TextAreaType::class, ['label' => 'Метрики Google', 'required' => false])
            ->add('fb_buttons', TextAreaType::class, ['label' => 'Код кнопок Facebook', 'required' => false])
            ->add('vk_buttons', TextAreaType::class, ['label' => 'Код кнопок Вконтакте', 'required' => false])
            ->add('seo_keywords', TextAreaType::class, ['label' => 'SEO - Keywords', 'required' => false])
            ->add('seo_description', TextAreaType::class, ['label' => 'SEO - Description', 'required' => false])
            ->add('seo_title', TextType::class, ['label' => 'SEO - Title', 'required' => false])
            ->add('enable_call_back', CheckboxType::class, ['label' => 'Использовать функцию "Заказать звонок"', 'required' => false])
            ->add('enable_sms', CheckboxType::class, ['label' => 'Отправлять SMS после заказа звонка', 'required' => false])
            ->add('save', SubmitType::class, array('label' => 'Сохранить'))
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->saveForm($form);
        } else {
            unset($data['logo']);
            unset($data['favicon']);
            $form->setData($data);
        }

        return $this->render('AppBundle:Admin:settings.html.twig', array(
            'base_template'   => $this->getBaseTemplate(),
            'admin_pool'      => $this->container->get('sonata.admin.pool'),
            'blocks'          => $this->container->getParameter('sonata.admin.configuration.dashboard_blocks'),
            'form'            => $form->createView(),
        ));
    }

    private function saveForm($form) {
        $data = $form->getData();
        $data['enable_call_back'] = (boolean)$data['enable_call_back'];
        $data['enable_sms'] = (boolean)$data['enable_sms'];

        if($data['logo']) {
            $this->prepareSystemFolder();
            $file = $form['logo']->getData();
            $ext = $file->guessExtension();
            $file->move(self::FOLDER . 'files/system/', 'logo.' . $ext);
            $data['logo'] = 'files/system/logo.' . $ext;
        } elseif($this->logo) {
            $data['logo'] = $this->logo;
        }
        if($data['favicon']) {
            $file = $form['favicon']->getData();
            $ext = $file->guessExtension();
            $file->move(self::FOLDER, 'favicon.' . $ext);
            unset($data['favicon']);
        }

        if(!$this->record) {
            $this->record = new Settings();
            $this->record->setName('site_settings');
        }

        $this->record->setSettings(serialize($data));
        $this->em->persist($this->record);
        $this->em->flush();
    }

    private function prepareSystemFolder(){
        $filesystem = new Filesystem();
        $filesystem->mkdir(self::FOLDER, 0744);
    }

}