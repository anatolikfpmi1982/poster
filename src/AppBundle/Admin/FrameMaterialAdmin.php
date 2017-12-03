<?php

namespace AppBundle\Admin;

use AppBundle\Entity\FrameMaterial;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use AppBundle\Service\ImageManagement;

class FrameMaterialAdmin extends AbstractAdmin
{
    /**
     * @var ImageManagement
     */
    protected $imageManagement;

    /**
     * Constructor
     *
     * @param string $code
     * @param string $class
     * @param string $baseControllerName
     * @param ImageManagement $imageManagement
     */
    public function __construct(
        $code,
        $class,
        $baseControllerName,
        ImageManagement $imageManagement
    ) {
        parent::__construct($code, $class, $baseControllerName);
        $this->imageManagement = $imageManagement;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Main')
            ->add('title', null, ['required' => true, 'label' => 'Название'])
            ->add('image', 'sonata_type_admin', ['required' => false, 'label' => 'Изображение'])
            ->add('ratio', null, ['required' => false, 'label' => 'Коэффициент', 'empty_data' => '1', 'attr' => ['placeholder' => 1]])
            ->add('price', null, ['required' => false, 'label' => 'Цена', 'empty_data' => '0', 'attr' => ['placeholder' => 0]])
            ->add('minArea', null, ['required' => false, 'label' => 'Минимальная площадь', 'empty_data' => '0', 'attr' => ['placeholder' => 0]])
            ->add('maxArea', null, ['required' => false, 'label' => 'Максимальная площадь', 'empty_data' => '0', 'attr' => ['placeholder' => 0]])
            ->add('minPrice', null, ['required' => false, 'label' => 'Минимальная цена', 'empty_data' => '0', 'attr' => ['placeholder' => 0]])
            ->add('maxPrice', null, ['required' => false, 'label' => 'Максимальная цена', 'empty_data' => '0', 'attr' => ['placeholder' => 0]])
            ->add('isActive', null, ['required' => false, 'label' => 'Показывать'])
            ->end();
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id', null, ['label' => 'ID'])
            ->add('image', null,
                ['label' => 'Изображение', 'template' => 'AppBundle:Admin:list_image.html.twig'])
            ->add('title', null, ['editable' => true, 'label' => 'Название'])
            ->add('ratio', null, ['editable' => true, 'label' => 'Коэффициент',
                'template' => 'AppBundle:Admin:list_field_float_editable.html.twig'])
            ->add('price', null, ['editable' => true, 'label' => 'Цена',
                'template' => 'AppBundle:Admin:list_field_float_editable.html.twig'])
            ->add('minArea', null, ['editable' => true, 'label' => 'Минимальная площадь',
                'template' => 'AppBundle:Admin:list_field_float_editable.html.twig'])
            ->add('maxArea', null, ['editable' => true, 'label' => 'Максимальная площадь',
                'template' => 'AppBundle:Admin:list_field_float_editable.html.twig'])
            ->add('minPrice', null, ['editable' => true, 'label' => 'Минимальная цена',
                'template' => 'AppBundle:Admin:list_field_float_editable.html.twig'])
            ->add('maxPrice', null, ['editable' => true, 'label' => 'Максимальная цена',
                'template' => 'AppBundle:Admin:list_field_float_editable.html.twig'])
//            ->add('createdAt', null, ['label' => 'Создано'])
//            ->add('updatedAt', null, ['label' => 'Опубликовано'])
            ->add('isActive', null, ['label' => 'Показывать', 'editable' => true])
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
            ->add('title', null, ['label' => 'Название'])
            ->add('isActive', null, ['label' => 'Показывать'])
        ;
    }

//    public function getBatchActions()
//    {
//        $actions = parent::getBatchActions();
//
//        if ($this->hasRoute('edit')) {
//
//            $actions['test'] = array(
//                'label'            => 'Test',
//                'ask_confirmation' => false
//            );
//        }
//
//        return $actions;
//    }
//
//    public function getTemplate($name)
//    {
//        switch ($name) {
//            case 'list':
//                return 'AppBundle:FrameMaterial:list.html.twig';
//                break;
//
//            default:
//                return parent::getTemplate($name);
//                break;
//        }
//    }

    /**
     * @param mixed $material
     */
    public function prePersist($material)
    {
        if($material instanceof FrameMaterial) {
            $material->setCreatedAt(new \DateTime());
            $material->setUpdatedAt(new \DateTime());
            $this->manageEmbeddedImageAdmins($material);
        }
    }

    /**
     * @param mixed $material
     */
    public function preUpdate($material)
    {
        if($material instanceof FrameMaterial) {
            $material->setUpdatedAt(new \DateTime());
            $this->manageEmbeddedImageAdmins($material);
        }
    }

    /**
     * @param mixed $material
     */
    public function postUpdate($material)
    {
        if($material instanceof FrameMaterial) {
            $this->imageManagement->cleanGarbageImages();
        }
    }

    /**
     * @param mixed $material
     */
    public function preRemove($material){
        if($material instanceof FrameMaterial) {
            $this->imageManagement->deleteImage($material->getImage());
        }
    }

    /**
     * @param FrameMaterial $material
     */
    private function manageEmbeddedImageAdmins(FrameMaterial $material)
    {
        // Cycle through each field
        foreach ($this->getFormFieldDescriptions() as $fieldName => $fieldDescription) {

            // detect embedded Admins that manage Images
            if ($fieldDescription->getType() === 'sonata_type_admin' &&
                ($associationMapping = $fieldDescription->getAssociationMapping()) &&
                $associationMapping['targetEntity'] === 'AppBundle\Entity\Image'
            ) {
                $getter = 'get'.$fieldName;
                $setter = 'set'.$fieldName;

                /** @var Image $image */
                $image = $material->$getter();

                if ($image) {
                    if ($image->getFile()) {
                        // update the Image to trigger file management
                        $image->refreshUpdated()
                            ->setCreatedAt(new \DateTime())
                            ->setEntityName($material::IMAGE_PATH);
                    } elseif (!$image->getFile() && !$image->getFilename()) {
                        // prevent Sf/Sonata trying to create and persist an empty Image
                        $material->$setter(null);
                    }
                }
            }
        }
    }
}