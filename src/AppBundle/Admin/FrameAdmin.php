<?php
namespace AppBundle\Admin;

use AppBundle\Entity\Frame;
use AppBundle\Entity\Image;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use AppBundle\Service\ImageManagement;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

/**
 * Admin class for frame
 */
class FrameAdmin extends AbstractAdmin
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
     * @param ImageManagement $imageManagement
     * @param EntityManager $entityManager
     */
    public function __construct(
        $code,
        $class,
        $baseControllerName,
        ImageManagement $imageManagement,
        EntityManager $entityManager
    ) {
        parent::__construct($code, $class, $baseControllerName);
        $this->imageManagement = $imageManagement;
        $this->em = $entityManager;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Main')
            ->add('title', null, ['required' => true, 'label' => 'Артикул'])
            ->add('description', CKEditorType::class, ['required' => false, 'label' => 'Описание'])
            ->add('height', null, ['required' => false, 'label' => 'Высота'])
            ->add('width', null, ['required' => false, 'label' => 'Ширина'])
            ->add('ratio', null, ['required' => false, 'label' => 'Коэффициент', 'empty_data' => '1', 'attr' => ['placeholder' => 1]])
            ->add('useRatio', null, ['required' => false, 'label' => 'Использовать коэффициент'])
//            ->add('color', 'choices', array('label' => 'Цвет',
//                'choices' => ['Status1' => 'Alias1', 'Status2' => 'Alias2']))
            ->add('color', null, array('label' => 'Цвет'))
            ->add('material', null, array('label' => 'Материал'))
            ->add('isActive', null, ['required' => false, 'label' => 'Показывать'])
            ->add('images', 'sonata_type_collection', array(
                'by_reference' => true,
                'label' => 'Изображения',
                'type_options' => array('delete' => true),
                'cascade_validation' => true,
                'btn_add' => 'Добавить изображение',
                'required' => false
            ),
                array(
                    'edit' => 'inline',
                    'inline' => 'table'
                ))
            ->end();
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $colorsChoices = [];
        $colors = $this->em->getRepository('AppBundle\Entity\FrameColor')->findBy(['isActive' => true]);
        if($colors) {
            foreach ($colors as $v) {
                $colorsChoices[$v->getId()] = (string)$v;
            }
        }
        $materialsChoices = [];
        $materials = $this->em->getRepository('AppBundle\Entity\FrameMaterial')->findBy(['isActive' => true]);
        if($materials) {
            foreach ($materials as $v) {
                $materialsChoices[$v->getId()] = (string)$v;
            }
        }

        $listMapper
            ->add('id', null, ['required' => true, 'label' => 'ID'])
            ->add('images', null,
                ['required' => true, 'label' => 'Изображение', 'template' => 'AppBundle:Admin:frame_list_image.html.twig'])
            ->add('title', null, ['editable'=> true, 'label' => 'Артикул'])
            ->add('height', null, ['editable' => true, 'label' => 'Высота',
                'template' => 'AppBundle:Admin:list_field_float_editable.html.twig'])
            ->add('width', null, ['editable' => true, 'label' => 'Ширина',
                'template' => 'AppBundle:Admin:list_field_float_editable.html.twig'])
            ->add('color', 'choice', ['editable' => true, 'label' => 'Цвет','editable' => true,
                'class' => 'Appbundle\Entity\FrameColor', 'choices' => $colorsChoices, 'sortable' => true,
                'sort_field_mapping'=> ['fieldName'=>'id'], 'sort_parent_association_mappings' => [['fieldName'=>'color']]])
            ->add('material', 'choice', ['editable' => true, 'label' => 'Материал','editable' => true,
                'class' => 'Appbundle\Entity\FrameMaterial', 'choices' => $materialsChoices, 'sortable' => true,
                'sort_field_mapping'=> ['fieldName'=>'id'], 'sort_parent_association_mappings' => [['fieldName'=>'material']]])
//            ->add('createdAt', null, ['label' => 'Создано'])
//            ->add('updatedAt', null, ['label' => 'Обновлено'])
            ->add('isActive', null, ['editable' => true, 'label' => 'Показывать'])
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
            ->add('title', null, ['label' => 'Артикул'])
            ->add('width', null, ['label' => 'Ширина'])
            ->add('height', null, ['label' => 'Высота'])
            ->add('color', null, ['label' => 'Цвет'])
            ->add('material', null, ['label' => 'Материал'])
            ->add('isActive', null, ['label' => 'Показывать'])
        ;
    }

    /**
     * @param mixed $moduleType
     */
    public function prePersist($moduleType)
    {
        if($moduleType instanceof Frame) {
            $moduleType->setCreatedAt(new \DateTime());
            $moduleType->setUpdatedAt(new \DateTime());
            $this->manageEmbeddedImageAdmins($moduleType);
        }
    }

    /**
     * @param mixed $moduleType
     */
    public function preUpdate($moduleType)
    {
        if($moduleType instanceof Frame) {
            $moduleType->setUpdatedAt(new \DateTime());
            $this->manageEmbeddedImageAdmins($moduleType);
        }
    }

    /**
     * @param mixed $moduleType
     */
    public function postUpdate($moduleType)
    {
        if($moduleType instanceof Frame) {
            $this->imageManagement->cleanGarbageImages();
        }
    }

    /**
     * @param mixed $moduleType
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function preRemove($moduleType){
        if($moduleType instanceof Frame) {
            $this->imageManagement->deleteImages($moduleType->getImages());
        }
    }

    /**
     * @param Frame $frame
     */
    private function manageEmbeddedImageAdmins(Frame $frame)
    {
        // Cycle through each field
        foreach ($this->getFormFieldDescriptions() as $fieldName => $fieldDescription) {

            // detect embedded Admins that manage Images
            if ($fieldDescription->getType() === 'sonata_type_collection' &&
                ($associationMapping = $fieldDescription->getAssociationMapping()) &&
                $associationMapping['targetEntity'] === 'AppBundle\Entity\Image'
            ) {
                $getter = 'get'.$fieldName;
                $setter = 'set'.$fieldName;

                /** @var ArrayCollection $image */
                $images = $frame->$getter();

                if (count($images)>0) {
                    foreach($images as $image) {
                        /** @var Image $image */;
                        if ($image->getFile()) {
                            // update the Image to trigger file management
                            $image->refreshUpdated()
                                ->setCreatedAt(new \DateTime())
                                ->setEntityName($frame::IMAGE_PATH);
                        } elseif (!$image->getFile() && !$image->getFilename()) {
                            // prevent Sf/Sonata trying to create and persist an empty Image
                            $frame->$setter(null);
                        }
                    }

                }
            }
        }
    }
}
