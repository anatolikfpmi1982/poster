<?php

namespace AppBundle\Admin;

use AppBundle\Entity\SliderItem;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use AppBundle\Service\ImageManagement;

class SliderAdmin extends AbstractAdmin
{
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
            ->add('title', null, ['required' => true, 'label' => 'Имя'])
            ->add('text', null, ['required' => false, 'label' => 'Описание'])
            ->add('link', null, ['required' => true, 'label' => 'Ссылка'])
            ->add('weight', null, ['required' => true, 'label' => 'Порядок'])
            ->add('image', 'sonata_type_admin', ['required' => true, 'label' => 'Изображение'])
            ->add('isActive', null, ['required' => false, 'label' => 'Показывать'])
            ->end();
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('image', null,
                ['required' => true, 'label' => 'Изображение', 'template' => 'AppBundle:Admin:slider_list_image.html.twig'])
            ->add('text', null, ['required' => true, 'label' => 'Описание', 'editable' => true])
            ->add('title', null, ['required' => true, 'label' => 'Имя', 'editable' => true])
            ->add('link', null, ['required' => true, 'label' => 'Ссылка', 'editable' => true])
            ->add('weight', null, ['required' => true, 'label' => 'Порядок', 'editable' => true])
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
            ->add('title', null, ['label' => 'Имя'])
            ->add('link', null, ['label' => 'Ссылка'])
            ->add('isActive', null, ['label' => 'Показывать'])
        ;
    }

    /**
     * @param mixed $slider
     */
    public function prePersist($slider)
    {
        if($slider instanceof SliderItem) {
            $slider->setCreatedAt(new \DateTime());
            $slider->setUpdatedAt(new \DateTime());
            $this->manageEmbeddedImageAdmins($slider);
        }
    }

    /**
     * @param mixed $slider
     */
    public function preUpdate($slider)
    {
        if($slider instanceof SliderItem) {
            $slider->setUpdatedAt(new \DateTime());
            $this->manageEmbeddedImageAdmins($slider);
        }
    }

    private function manageEmbeddedImageAdmins(SliderItem $slider)
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
                $image = $slider->$getter();

                if ($image) {
                    if ($image->getFile()) {
                        // update the Image to trigger file management
                        $image->refreshUpdated()
                            ->setCreatedAt(new \DateTime())
                            ->setEntityName($slider::IMAGE_PATH);
                    } elseif (!$image->getFile() && !$image->getFilename()) {
                        // prevent Sf/Sonata trying to create and persist an empty Image
                        $slider->$setter(null);
                    }
                }
            }
        }
    }
}