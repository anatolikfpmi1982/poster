<?php
namespace AppBundle\Admin;

use AppBundle\Entity\Order;
use Doctrine\ORM\EntityManager;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

/**
 * Admin class for order
 */
class OrderAdmin extends AbstractAdmin
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * Constructor
     *
     * @param string $code
     * @param string $class
     * @param string $baseControllerName
     * @param EntityManager $entityManager
     */
    public function __construct(
        $code,
        $class,
        $baseControllerName,
        EntityManager $entityManager
    ) {
        parent::__construct($code, $class, $baseControllerName);
        $this->em = $entityManager;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $subject = $this->getSubject();
        if($subject->getPicture()) {
            $formMapper
                ->with('Main')
                ->add('picture', 'sonata_type_admin', array('label' => 'Картина', 'disabled' => true))
                ->end();
        } else {
            $formMapper
                ->with('Main')
                ->add('ownPicture', 'sonata_type_admin', array('label' => 'Загруженная картина', 'disabled' => true))
                ->end();
        }

        $formMapper
            ->with('Main')
            ->add('type', 'choice', array('label' => 'Тип картины', 'choices' => ['banner' => 'Баннер', 'frame' => 'В раме', 'module' => 'Модульная']))
            ->add('fullname', null, ['required' => true, 'label' => 'Ф.И.О.'])
            ->add('email', EmailType::class, ['required' => true, 'label' => 'Email'])
            ->add('phone', null, ['required' => true, 'label' => 'Телефон'])
            ->add('city', null, ['required' => true, 'label' => 'Город'])
            ->add('address', null, ['required' => true, 'label' => 'Адрес'])
            ->add('company', null, ['required' => false, 'label' => 'Компания'])
            ->add('comment', null, ['required' => false, 'label' => 'Комментарий к заказу'])
            ->add('height', null, ['required' => false, 'label' => 'Высота', 'empty_data' => '0', 'attr' => ['placeholder' => 0]])
            ->add('width', null, ['required' => false, 'label' => 'Ширина', 'empty_data' => '0', 'attr' => ['placeholder' => 0]])
            ->add('price', null, ['required' => false, 'label' => 'Цена', 'empty_data' => '0', 'attr' => ['placeholder' => 0]])
            ->add('frame', null, array('label' => 'Рама'))
            ->add('frameMaterial', null, array('label' => 'Материал для картины в раме'))
            ->add('bannerMaterial', null, array('label' => 'Материал для баннера'))
            ->add('underframe', null, array('label' => 'Подрамник'))
            ->add('frameColor', null, array('label' => 'Цвет рамы'))
            ->add('material', null, array('label' => 'Материал рамы'))
            ->add('moduleType', null, array('label' => 'Тип модульности'))
            ->add('isActive', null, ['required' => false, 'label' => 'Взято в работу'])
            ->add('isDone', null, ['required' => false, 'label' => 'Выполнено'])
            ->end();
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $bannerMaterialsChoices = [];
        $materials = $this->em->getRepository('AppBundle\Entity\BannerMaterial')->findBy(['isActive' => true]);
        if($materials) {
            foreach ($materials as $v) {
                $bannerMaterialsChoices[$v->getId()] = (string)$v;
            }
        }

        $frameMaterialsChoices = [];
        $materials = $this->em->getRepository('AppBundle\Entity\FrameMaterial')->findBy(['isActive' => true]);
        if($materials) {
            foreach ($materials as $v) {
                $frameMaterialsChoices[$v->getId()] = (string)$v;
            }
        }

        $frameChoices = [];
        $materials = $this->em->getRepository('AppBundle\Entity\Frame')->findBy(['isActive' => true]);
        if($materials) {
            foreach ($materials as $v) {
                $frameChoices[$v->getId()] = (string)$v;
            }
        }

        $underframeChoices = [];
        $materials = $this->em->getRepository('AppBundle\Entity\Underframe')->findBy(['isActive' => true]);
        if($materials) {
            foreach ($materials as $v) {
                $underframeChoices[$v->getId()] = (string)$v;
            }
        }

        $moduleTypeChoices = [];
        $materials = $this->em->getRepository('AppBundle\Entity\ModuleType')->findBy(['isActive' => true]);
        if($materials) {
            foreach ($materials as $v) {
                $moduleTypeChoices[$v->getId()] = (string)$v;
            }
        }

        $listMapper
            ->add('id', null, ['required' => true, 'label' => 'ID'])
            ->add('groupId', null, ['editable'=> false, 'label' => 'Заказ'])
            ->add('picture.image', null,
                ['label' => 'Изображение', 'template' => 'AppBundle:Admin:order_list_image.html.twig'])
            ->add('ownPicture.image', null,
                ['label' => 'Своё изображение', 'template' => 'AppBundle:Admin:order_list_own_image.html.twig'])
            ->add('picture.code', null, ['editable'=> false, 'label' => 'Артикул'])
            ->add('picture.title', null, ['editable'=> false, 'label' => 'Название'])
            ->add('type', null, ['editable' => false, 'label' => 'Тип',
                'template' => 'AppBundle:Admin:order_list_type.html.twig'])
            ->add('frame', 'choice', ['label' => 'Рама','editable' => true,
                'class' => 'Appbundle\Entity\Frame', 'choices' => $frameChoices, 'sortable' => true,
                'sort_field_mapping'=> ['fieldName'=>'id'], 'sort_parent_association_mappings' => [['fieldName'=>'frame']]])
            ->add('moduleType', 'choice', ['label' => 'Модульность','editable' => true,
                'class' => 'Appbundle\Entity\ModuleType', 'choices' => $moduleTypeChoices, 'sortable' => true,
                'sort_field_mapping'=> ['fieldName'=>'id'], 'sort_parent_association_mappings' => [['fieldName'=>'moduleType']]])
            ->add('bannerMaterial', 'choice', ['label' => 'Материал для баннера','editable' => true,
                'class' => 'Appbundle\Entity\BannerMaterial', 'choices' => $bannerMaterialsChoices, 'sortable' => true,
                'sort_field_mapping'=> ['fieldName'=>'id'], 'sort_parent_association_mappings' => [['fieldName'=>'bannerMaterial']]])
            ->add('frameMaterial', 'choice', ['label' => 'Материал для картины в раме','editable' => true,
                'class' => 'Appbundle\Entity\FrameMaterial', 'choices' => $frameMaterialsChoices, 'sortable' => true,
                'sort_field_mapping'=> ['fieldName'=>'id'], 'sort_parent_association_mappings' => [['fieldName'=>'frameMaterial']]])
            ->add('fullname', null, ['editable'=> true, 'label' => 'Ф.И.О.'])
            ->add('email', null, ['editable'=> true, 'label' => 'Email'])
            ->add('phone', null, ['editable'=> true, 'label' => 'Телефон'])
            ->add('city', null, ['editable'=> true, 'label' => 'Город'])
            ->add('address', null, ['editable'=> true, 'label' => 'Адрес'])
            ->add(
                '_action',
                'actions',
                [
                    'actions' => [
                        'edit' => [],
                        'delete' => [],
                    ],
                ]
            )
            ->add('height', null, ['editable'=> true, 'label' => 'Высота'])
            ->add('width', null, ['editable'=> true, 'label' => 'Ширина'])
            ->add('price', null, ['editable' => true, 'label' => 'Цена',
                'template' => 'AppBundle:Admin:list_field_float_editable.html.twig'])
            ->add('isActive', null, ['editable' => true, 'label' => 'Взято в работу'])
            ->add('isDone', null, ['editable' => true, 'label' => 'Выполнено'])
            ->add('createdAt', null, ['label' => 'Дата заказа'])
            ->add('company', null, ['editable'=> true, 'label' => 'Компания'])
            ->add('comment', null, ['editable'=> true, 'label' => 'Комментарий к заказу'])
            ->add('underframe', 'choice', ['label' => 'Толщина подрамника','editable' => true,
                'class' => 'Appbundle\Entity\Underframe', 'choices' => $underframeChoices, 'sortable' => true,
                'sort_field_mapping'=> ['fieldName'=>'id'], 'sort_parent_association_mappings' => [['fieldName'=>'underframe']]])
            ->add('frame.note', null, ['editable'=> false, 'label' => 'Рама примечание'])
            ->add('picture.note', null, ['editable' => false, 'label' => 'Примечание']);
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('groupId', null, ['label' => 'Номер заказа'])
            ->add('picture.code', null, ['label' => 'Артикул'])
            ->add('picture.title', null, ['label' => 'Название'])
            ->add('type', null, ['label' => 'Тип'])
            ->add('frame', null, ['label' => 'Рама'])
            ->add('moduleType', null, ['label' => 'Модульность'])
            ->add('bannerMaterial', null, ['label' => 'Материал для баннера'])
            ->add('frameMaterial', null, ['label' => 'Материал для картины в раме'])
            ->add('fullname', null, ['label' => 'Ф.И.О.'])
            ->add('email', null, ['label' => 'Email'])
            ->add('phone', null, ['label' => 'Телефон'])
            ->add('city', null, ['label' => 'Город'])
            ->add('address', null, ['label' => 'Адрес'])
            ->add('height', null, ['label' => 'Высота'])
            ->add('width', null, ['label' => 'Ширина'])
            ->add('price', null, ['label' => 'Цена'])
            ->add('createdAt', 'doctrine_orm_date_range', ['label' => 'Дата заказа'])
            ->add('company', null, ['label' => 'Компания'])
            ->add('comment', null, ['label' => 'Комментарий к заказу'])
            ->add('underframe', null, ['label' => 'Толщина подрамника'])
            ->add('frame.note', null, ['label' => 'Рама примечание'])
            ->add('picture.note', null, ['label' => 'Примечание'])
            ->add('isActive', null, ['label' => 'Взято в работу'])
            ->add('isDone', null, ['label' => 'Выполнено'])
        ;
    }

    /**
     * Configure admin
     */
    public function configure() {
        $this->setTemplate('list', 'AppBundle:Admin:list_javascript.html.twig');
    }

    /**
     * @param Order $order
     */
    public function prePersist($order)
    {
        if($order instanceof Order) {
            $order->setCreatedAt(new \DateTime());
            $order->setUpdatedAt(new \DateTime());
            $this->clearEntities($order);
        }
    }

    /**
     * @param Order $order
     */
    public function preUpdate($order)
    {
        if($order instanceof Order) {
            $order->setUpdatedAt(new \DateTime());
            $this->clearEntities($order);
        }
    }

    /**
     * Delete entities dependencies
     *
     * @param Order $order
     * @return Order
     */
    private function clearEntities(Order $order) {
        if($order->getType() == 'banner') {
            $order->setFrame(null);
            $order->setFrameMaterial(null);
            $order->setFrameColor(null);
            $order->setMaterial(null);
            $order->setModuleType(null);
        } elseif($order->getType() == 'frame') {
            $order->setBannerMaterial(null);
            $order->setUnderframe(null);
            $order->setModuleType(null);
        } elseif($order->getType() == 'module') {
            $order->setFrame(null);
            $order->setFrameMaterial(null);
            $order->setFrameColor(null);
            $order->setMaterial(null);
            $order->setModuleType(null);
        }

        return $order;
    }
}
