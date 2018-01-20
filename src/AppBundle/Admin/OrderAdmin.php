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
            ->add('type', 'choice', array('label' => 'Тип картины', 'choices' => ['banner' => 'Баннер', 'frame' => 'В раме', 'module' => 'Панно']))
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
            ->add('frameMaterial', null, array('label' => 'Материал картины в раме'))
            ->add('bannerMaterial', null, array('label' => 'Материал баннера'))
            ->add('underframe', null, array('label' => 'Подрамник'))
            ->add('frameColor', null, array('label' => 'Цвет рамы'))
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
            ->add('bannerMaterial', null, ['editable'=> false, 'label' => 'Материал баннера'])
            ->add('frameMaterial', null, ['editable'=> false, 'label' => 'Материал рамы'])
            ->add('fullname', null, ['editable'=> true, 'label' => 'Ф.И.О.'])
            ->add('email', null, ['editable'=> true, 'label' => 'Email'])
            ->add('phone', null, ['editable'=> true, 'label' => 'Телефон'])
            ->add('city', null, ['editable'=> true, 'label' => 'Город'])
            ->add('address', null, ['editable'=> true, 'label' => 'Адрес'])
            ->add('company', null, ['editable'=> true, 'label' => 'Компания'])
            ->add('height', null, ['editable'=> true, 'label' => 'Высота'])
            ->add('width', null, ['editable'=> true, 'label' => 'Ширина'])
            ->add('price', null, ['editable' => true, 'label' => 'Цена',
                'template' => 'AppBundle:Admin:list_field_float_editable.html.twig'])
            ->add('isActive', null, ['editable' => true, 'label' => 'Взято в работу'])
            ->add('isDone', null, ['editable' => true, 'label' => 'Выполнено'])
            ->add('createdAt', null, ['label' => 'Дата заказа'])
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
            ->add('picture.note', null, ['editable' => false, 'label' => 'Примечание']);
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('groupId', null, ['label' => 'Номер заказа'])
//            ->add('title', null, ['label' => 'Артикул'])
//            ->add('width', null, ['label' => 'Ширина'])
//            ->add('height', null, ['label' => 'Высота'])
//            ->add('color', null, ['label' => 'Цвет'])
//            ->add('material', null, ['label' => 'Материал'])
            ->add('isActive', null, ['label' => 'Взято в работу'])
            ->add('isDone', null, ['label' => 'Выполнено'])
        ;
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
            $order->setModuleType(null);
        } elseif($order->getType() == 'frame') {
            $order->setBannerMaterial(null);
            $order->setUnderframe(null);
            $order->setModuleType(null);
        } elseif($order->getType() == 'module') {
            $order->setFrame(null);
            $order->setFrameMaterial(null);
            $order->setModuleType(null);
        }

        return $order;
    }
}
