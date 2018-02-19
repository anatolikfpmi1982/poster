<?php
namespace AppBundle\Controller;
error_reporting(E_ALL);
use AppBundle\Entity\Settings;
use Doctrine\ORM\EntityManager;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Controller\CoreController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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

    /**
     * @var Settings
     */
    private $record = null;

    /**
     * @var string
     */
    private $logo = null;

    /**
     * @var string
     */
    private $favicon = null;

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function settingsAction(Request $request)
    {
        $this->em = $this->container->get('doctrine.orm.entity_manager');
        $this->record = $this->em->getRepository('AppBundle:Settings')->findOneByName('site_settings');
        if($this->record) {
            $data = unserialize($this->record->getSettings());
            if(array_key_exists('phone', $data)) {
                $data['info_text'] = $data['phone'];
                unset($data['phone']);
            }
            $this->logo = '';
            $this->favicon = '';
            if(!empty($data['logo'])) {
                $this->logo = $data['logo'];
            }
            if(!empty($data['favicon'])) {
                $this->favicon = $data['favicon'];
            }
        } else {
            $data = [
                'info_text' => '',
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
                'enable_own_picture' => false,
                'enable_call_back' => false,
                'enable_reviews' => false,
                'enable_banner' => true,
                'enable_frame' => true,
                'enable_module' => true,
                'contact_us_text' => '',
                'from_email' => '',
                'to_email' => '',
                'my_files_description' => '',
                'min_price' => ''
            ];
        }

        $form = $this->createFormBuilder()
            ->add('info_text', CKEditorType::class, ['label' => 'Блок информации(шапка и футер)', 'required' => false])
            ->add('email', EmailType::class, ['label' => 'Email', 'required' => false])
            ->add('title', TextType::class, ['label' => 'Заголовок под слайдером', 'required' => false])
            ->add('under_slider_text', CKEditorType::class, ['label' => 'Текст под слайдером', 'required' => false])
            ->add('logo_header_text', TextareaType::class, ['label' => 'Текст под лого (шапка)', 'required' => false])
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
            ->add('contact_us_text', CKEditorType::class, ['label' => 'Текст над формой в "Свяжись с нами"', 'required' => false])
            ->add('frame_page_text', CKEditorType::class, ['label' => 'Текст на странице Рам', 'required' => false])
            ->add('block_todo_text', CKEditorType::class, ['label' => 'Текст в блоке "Не можете найти?"', 'required' => false])
            ->add('my_files_description', CKEditorType::class, ['label' => 'Текст на странице Моих файлов', 'required' => false])
            ->add('from_email', TextType::class, ['label' => 'Email сайта', 'required' => false])
            ->add('to_email', TextType::class, ['label' => 'Email получателя', 'required' => false])
            ->add('enable_own_picture', CheckboxType::class, ['label' => 'Использовать функцию "Загрузить картину"', 'required' => false])
            ->add('enable_call_back', CheckboxType::class, ['label' => 'Использовать функцию "Заказать звонок"', 'required' => false])
            ->add('enable_reviews', CheckboxType::class, ['label' => 'Показывать блок "Отзывы"', 'required' => false])
            ->add('enable_banner', CheckboxType::class, ['label' => 'Показывать "Баннер" в конструкторе', 'required' => false])
            ->add('enable_frame', CheckboxType::class, ['label' => 'Показывать "В раме" в конструкторе', 'required' => false])
            ->add('enable_module', CheckboxType::class, ['label' => 'Показывать "Модульная" в конструкторе', 'required' => false])
            ->add('min_price', NumberType::class, ['label' => 'Минимальная цена', 'required' => false])
            ->add('save', SubmitType::class, array('label' => 'Сохранить'))
            ->getForm();

        $data['logo'] = $data['favicon'] = null;
        $form->setData( $data );

        if($request->getMethod() === 'POST') {
            $form->handleRequest( $request );

            if ( $form->isValid() ) {
                $this->saveForm( $form );
            } else {
                throw new BadRequestHttpException($form->getErrors());
            }
        }

        return $this->render('AppBundle:Admin:settings.html.twig', [
            'base_template'   => $this->getBaseTemplate(),
            'admin_pool'      => $this->container->get('sonata.admin.pool'),
            'blocks'          => $this->container->getParameter('sonata.admin.configuration.dashboard_blocks'),
            'form'            => $form->createView(),
            'logo_value'      => $this->logo,
            'favicon_value'   => $this->favicon,
        ]);
    }

    public function frameSettingsAction(Request $request)
    {
        $this->em = $this->container->get('doctrine.orm.entity_manager');
        $this->record = $this->em->getRepository('AppBundle:Settings')->findOneByName('frame_settings');
        if($this->record) {
            $data = unserialize($this->record->getSettings());
        } else {
            $data = [
                'minArea' => 0,
                'maxArea' => 0,
                'minPrice' => 0,
                'maxPrice' => 0,
                'price' => 0
            ];
        }

        $form = $this->createFormBuilder()
            ->add('minArea', NumberType::class, ['label' => 'Минимальная площадь', 'required' => false])
            ->add('maxArea', NumberType::class, ['label' => 'Максимальная площадь', 'required' => false])
            ->add('minPrice', NumberType::class, ['label' => 'Минимальная цена', 'required' => false])
            ->add('maxPrice', NumberType::class, ['label' => 'Максимальная цена', 'required' => false])
            ->add('price', NumberType::class, ['label' => 'Добавочная цена', 'required' => false])
            ->add('save', SubmitType::class, array('label' => 'Сохранить'))
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->saveFrameSettingsForm($form);
        } else {
            $form->setData($data);
        }

        return $this->render('AppBundle:Admin:frame_settings.html.twig', array(
            'base_template'   => $this->getBaseTemplate(),
            'admin_pool'      => $this->container->get('sonata.admin.pool'),
            'blocks'          => $this->container->getParameter('sonata.admin.configuration.dashboard_blocks'),
            'form'            => $form->createView(),
        ));
    }

    public function helpSettingsAction(Request $request)
    {
        $this->em = $this->container->get('doctrine.orm.entity_manager');
        $this->record = $this->em->getRepository('AppBundle:Settings')->findOneByName('help_settings');
        if($this->record) {
            $data = unserialize($this->record->getSettings());
        } else {
            $data = [
                'typeHelp' => '',
                'sizeHelp' => '',
                'materialBannerHelp' => '',
                'materialPictureHelp' => '',
                'underframeHelp' => '',
                'chooseHelp' => '',
            ];
        }

        $form = $this->createFormBuilder()
            ->add('typeHelp', CKEditorType::class, ['label' => 'Тип картины', 'required' => false])
            ->add('sizeHelp', CKEditorType::class, ['label' => 'Размер картины', 'required' => false])
            ->add('materialBannerHelp', CKEditorType::class, ['label' => 'Материал баннера', 'required' => false])
            ->add('materialPictureHelp', CKEditorType::class, ['label' => 'Материал картины', 'required' => false])
            ->add('underframeHelp', CKEditorType::class, ['label' => 'Подрамник', 'required' => false])
            ->add('chooseHelp', CKEditorType::class, ['label' => 'Выбрано', 'required' => false])
            ->add('save', SubmitType::class, array('label' => 'Сохранить'))
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->saveHelpSettingsForm($form);
        } else {
            $form->setData($data);
        }

        return $this->render('AppBundle:Admin:help_settings.html.twig', array(
            'base_template'   => $this->getBaseTemplate(),
            'admin_pool'      => $this->container->get('sonata.admin.pool'),
            'blocks'          => $this->container->getParameter('sonata.admin.configuration.dashboard_blocks'),
            'form'            => $form->createView(),
        ));
    }

    private function saveForm($form) {
        $data = $form->getData();
        $data['enable_call_back'] = (boolean)$data['enable_call_back'];
        $data['enable_reviews'] = (boolean)$data['enable_reviews'];
        $data['enable_banner'] = (boolean)$data['enable_banner'];
        $data['enable_frame'] = (boolean)$data['enable_frame'];
        $data['enable_module'] = (boolean)$data['enable_module'];
        $data['enable_own_picture'] = (boolean)$data['enable_own_picture'];

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
            $this->prepareSystemFolder();
            $file = $form['favicon']->getData();
            $ext = $file->guessExtension();
            $file->move(self::FOLDER . 'files/system/', 'favicon.' . $ext);
            $data['favicon'] = 'files/system/favicon.' . $ext;
        } elseif($this->favicon) {
            $data['favicon'] = $this->favicon;
        }

        if(!$this->record) {
            $this->record = new Settings();
            $this->record->setName('site_settings');
        }

        $this->record->setSettings(serialize($data));
        $this->em->persist($this->record);
        $this->em->flush();
    }

    private function saveFrameSettingsForm($form) {
        $data = $form->getData();
        if(!$this->record) {
            $this->record = new Settings();
            $this->record->setName('frame_settings');
        }

        $this->record->setSettings(serialize($data));
        $this->em->persist($this->record);
        $this->em->flush();
    }

    private function saveHelpSettingsForm($form) {
        $data = $form->getData();
        if(!$this->record) {
            $this->record = new Settings();
            $this->record->setName('help_settings');
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