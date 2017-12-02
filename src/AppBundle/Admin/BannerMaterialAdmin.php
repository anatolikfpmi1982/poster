<?php

namespace AppBundle\Admin;

use AppBundle\Entity\BannerMaterial;
use AppBundle\Entity\Image;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use AppBundle\Service\ImageManagement;

class BannerMaterialAdmin extends AbstractAdmin
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
            ->add('ratio', null, ['required' => false, 'label' => 'Коэффициент'])
            ->add('minArea', null, ['required' => false, 'label' => 'Минимальная площадь'])
            ->add('maxArea', null, ['required' => false, 'label' => 'Максимальная площадь'])
            ->add('minPrice', null, ['required' => false, 'label' => 'Минимальная цена'])
            ->add('maxPrice', null, ['required' => false, 'label' => 'Максимальная цена'])
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
            ->add('ratio', null, ['editable' => true, 'label' => 'Коэффициент'])
            ->add('minArea', null, ['editable' => true, 'label' => 'Минимальная площадь'])
            ->add('maxArea', null, ['editable' => true, 'label' => 'Максимальная площадь'])
            ->add('minPrice', null, ['editable' => true, 'label' => 'Минимальная цена'])
            ->add('maxPrice', null, ['editable' => true, 'label' => 'Максимальная цена'])
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

    /**
     * @param mixed $material
     */
    public function prePersist($material)
    {
        if($material instanceof BannerMaterial) {
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
        if($material instanceof BannerMaterial) {
            $material->setUpdatedAt(new \DateTime());
            $this->manageEmbeddedImageAdmins($material);
        }
    }

    /**
     * @param mixed $material
     */
    public function postUpdate($material)
    {
        if($material instanceof BannerMaterial) {
            $this->imageManagement->cleanGarbageImages();
        }
    }

    /**
     * @param mixed $material
     */
    public function preRemove($material){
        if($material instanceof BannerMaterial) {
            $this->imageManagement->deleteImage($material->getImage());
        }
    }

    /**
     * @param BannerMaterial $material
     */
    private function manageEmbeddedImageAdmins(BannerMaterial $material)
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