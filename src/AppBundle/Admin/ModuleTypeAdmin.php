<?php
namespace AppBundle\Admin;

use AppBundle\Entity\ModuleType;
use AppBundle\Entity\Image;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use AppBundle\Service\ImageManagement;

/**
 * Admin class for module type
 */
class ModuleTypeAdmin extends AbstractAdmin
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
            ->add('name', null, ['required' => true, 'label' => 'Название'])
            ->add('ratio', null, ['required' => false, 'label' => 'Коэффициент'])
            ->add('serviceName', null, ['required' => true, 'label' => 'Сервисное имя (не изменять без необходимости)'])
            ->add('isActive', null, ['required' => false, 'label' => 'Показывать'])
            ->end();
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
//            ->add('images', null,
//                ['required' => true, 'label' => 'Изображение', 'template' => 'AppBundle:Admin:frame_list_image.html.twig'])
            ->add('name', null, ['editable'=> true, 'label' => 'Название'])
            ->add('ratio', null, ['editable' => true, 'label' => 'Коэффициент'])
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
            ->add('name', null, ['label' => 'Название'])
            ->add('isActive', null, ['label' => 'Показывать'])
        ;
    }

    /**
     * @param mixed $moduleType
     */
    public function prePersist($moduleType)
    {
        if($moduleType instanceof ModuleType) {
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
        if($moduleType instanceof ModuleType) {
            $moduleType->setUpdatedAt(new \DateTime());
            $this->manageEmbeddedImageAdmins($moduleType);
        }
    }

    /**
     * @param mixed $moduleType
     */
    public function postUpdate($moduleType)
    {
        if($moduleType instanceof ModuleType) {
            $this->imageManagement->cleanGarbageImages();
        }
    }

    /**
     * @param mixed $moduleType
     */
    public function preRemove($moduleType){
        if($moduleType instanceof ModuleType) {
            $this->imageManagement->deleteImages($moduleType->getImages());
        }
    }

    /**
     * @param ModuleType $moduleType
     */
    private function manageEmbeddedImageAdmins(ModuleType $moduleType)
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
                $images = $moduleType->$getter();

                if (count($images)>0) {
                    foreach($images as $image) {
                        /** @var Image $image */;
                        if ($image->getFile()) {
                            // update the Image to trigger file management
                            $image->refreshUpdated()
                                ->setCreatedAt(new \DateTime())
                                ->setEntityName($moduleType::IMAGE_PATH);
                        } elseif (!$image->getFile() && !$image->getFilename()) {
                            // prevent Sf/Sonata trying to create and persist an empty Image
                            $moduleType->$setter(null);
                        }
                    }

                }
            }
        }
    }
}
