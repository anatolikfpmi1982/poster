<?php
namespace AppBundle\Admin;

use AppBundle\Entity\Frame;
use AppBundle\Entity\Image;
use AppBundle\Service\ImageManagement;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\FloatType;
use Doctrine\ORM\EntityManager;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
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
     * @var ImageManagement
     */
    protected $imageManagement;

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
        $formMapper
            ->with('Main')
            ->add('fullname', null, ['required' => true, 'label' => 'Ф.И.О.'])
            ->add('email', EmailType::class, ['required' => true, 'label' => 'Email'])
            ->add('phone', null, ['required' => true, 'label' => 'Телефон'])
            ->add('city', null, ['required' => true, 'label' => 'Город'])
            ->add('address', null, ['required' => true, 'label' => 'Адрес'])
            ->add('address2', null, ['required' => false, 'label' => 'Адрес 2'])
            ->add('company', null, ['required' => false, 'label' => 'Компания'])
            ->add('fax', null, ['required' => false, 'label' => 'Факс'])
            ->add('comment', null, ['required' => false, 'label' => 'Комментарий к заказу'])
            ->add('height', null, ['required' => false, 'label' => 'Высота', 'empty_data' => '0', 'attr' => ['placeholder' => 0]])
            ->add('width', null, ['required' => false, 'label' => 'Ширина', 'empty_data' => '0', 'attr' => ['placeholder' => 0]])
            ->add('price', null, ['required' => false, 'label' => 'Цена', 'empty_data' => '0', 'attr' => ['placeholder' => 0]])
            ->add('frameMaterial', null, array('label' => 'Материал картины в раме'))
            ->add('picture', null, array('label' => 'Картина'))
            ->add('bannerMaterial', null, array('label' => 'Материал баннера'))
            ->add('underframe', null, array('label' => 'Подрамник'))
            ->add('frameColor', null, array('label' => 'Цвет рамы'))
            ->add('moduleType', null, array('label' => 'Тип модульности'))
            ->add('isActive', null, ['required' => false, 'label' => 'Выполнено'])
            ->end();
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
//        $frameMaterialsChoices = [];
//        $frameMaterials = $this->em->getRepository('AppBundle\Entity\FrameMaterial')->findBy(['isActive' => true]);
//        if($frameMaterials) {
//            foreach ($frameMaterials as $v) {
//                $frameMaterialsChoices[$v->getId()] = (string)$v;
//            }
//        }
//        $bannerMaterialsChoices = [];
//        $bannerMaterials = $this->em->getRepository('AppBundle\Entity\BannerMaterial')->findBy(['isActive' => true]);
//        if($bannerMaterials) {
//            foreach ($bannerMaterials as $v) {
//                $bannerMaterialsChoices[$v->getId()] = (string)$v;
//            }
//        }

        $listMapper
            ->add('id', null, ['required' => true, 'label' => 'ID'])
//            ->add('images', null,
//                ['required' => true, 'label' => 'Изображение', 'template' => 'AppBundle:Admin:frame_list_image.html.twig'])
            ->add('fullname', null, ['editable'=> true, 'label' => 'Ф.И.О.'])
            ->add('email', null, ['editable'=> true, 'label' => 'Email'])
            ->add('phone', null, ['editable'=> true, 'label' => 'Телефон'])
            ->add('city', null, ['editable'=> true, 'label' => 'Город'])
            ->add('address', null, ['editable'=> true, 'label' => 'Адрес'])
            ->add('address2', null, ['editable'=> true, 'label' => 'Адрес 2'])
            ->add('company', null, ['editable'=> true, 'label' => 'Компания'])
            ->add('fax', null, ['editable'=> true, 'label' => 'Факс'])
            ->add('height', null, ['editable'=> true, 'label' => 'Высота'])
            ->add('width', null, ['editable'=> true, 'label' => 'Ширина'])
            ->add('price', null, ['editable' => true, 'label' => 'Цена',
                'template' => 'AppBundle:Admin:list_field_float_editable.html.twig'])
//            ->add('height', null, ['editable' => true, 'label' => 'Высота',
//                'template' => 'AppBundle:Admin:list_field_float_editable.html.twig'])
//            ->add('width', null, ['editable' => true, 'label' => 'Ширина',
//                'template' => 'AppBundle:Admin:list_field_float_editable.html.twig'])
//            ->add('bannerMaterial', 'choice', ['editable' => true, 'label' => 'Материал баннера','editable' => true,
//                'class' => 'Appbundle\Entity\BannerMaterial', 'choices' => $bannerMaterialsChoices, 'sortable' => true,
//                'sort_field_mapping'=> ['fieldName'=>'id'], 'sort_parent_association_mappings' => [['fieldName'=>'bannerMaterial']]])
//            ->add('frameMaterial', 'choice', ['editable' => true, 'label' => 'Материал картины в раме','editable' => true,
//                'class' => 'Appbundle\Entity\BannerMaterial', 'choices' => $bannerMaterialsChoices, 'sortable' => true,
//                'sort_field_mapping'=> ['fieldName'=>'id'], 'sort_parent_association_mappings' => [['fieldName'=>'material']]])
            ->add('isActive', null, ['editable' => true, 'label' => 'Выполнено'])
            ->add(
                '_action',
                'actions',
                [
                    'actions' => [
                        'edit' => [],
                        'delete' => [],
                    ],
                ]
            );
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
//            ->add('title', null, ['label' => 'Артикул'])
//            ->add('width', null, ['label' => 'Ширина'])
//            ->add('height', null, ['label' => 'Высота'])
//            ->add('color', null, ['label' => 'Цвет'])
//            ->add('material', null, ['label' => 'Материал'])
            ->add('isActive', null, ['label' => 'Выполнено'])
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
        }
    }

    /**
     * @param Order $order
     */
    public function preUpdate($order)
    {
        if($order instanceof Order) {
            $order->setUpdatedAt(new \DateTime());
        }
    }
}
