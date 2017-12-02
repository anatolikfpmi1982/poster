<?php
namespace AppBundle\Admin;

use AppBundle\Entity\Underframe;
use AppBundle\Entity\Image;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use AppBundle\Service\ImageManagement;

/**
 * Admin class for underframe
 */
class UnderframeAdmin extends AbstractAdmin
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
            ->add('image', 'sonata_type_admin', ['required' => false, 'label' => 'Изображение'])
            ->add('depth', null, ['required' => false, 'label' => 'Толщина'])
            ->add('price', null, ['required' => false, 'label' => 'Цена', 'empty_data' => '0', 'attr' => ['placeholder' => 0]])
            ->add('ratio', null, ['required' => false, 'label' => 'Коэффициент',  'empty_data' => '1', 'attr' => ['placeholder' => 1]])
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
            ->add('depth', null, ['editable' => true, 'label' => 'Толщина'])
            ->add('price', null, ['editable' => true, 'label' => 'Цена'])
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
            ->add('isActive', null, ['label' => 'Показывать'])
        ;
    }

    /**
     * @param mixed $underframe
     */
    public function prePersist($underframe)
    {
        if($underframe instanceof Underframe) {
            $underframe->setCreatedAt(new \DateTime());
            $underframe->setUpdatedAt(new \DateTime());
            $this->manageEmbeddedImageAdmins($underframe);
        }
    }

    /**
     * @param mixed $underframe
     */
    public function preUpdate($underframe)
    {
        if($underframe instanceof Underframe) {
            $underframe->setUpdatedAt(new \DateTime());
            $this->manageEmbeddedImageAdmins($underframe);
        }
    }

    /**
     * @param mixed $underframe
     */
    public function postUpdate($underframe)
    {
        if($underframe instanceof Underframe) {
            $this->imageManagement->cleanGarbageImages();
        }
    }

    /**
     * @param mixed $underframe
     */
    public function preRemove($underframe){
        if($underframe instanceof Underframe) {
            $this->imageManagement->deleteImage($underframe->getImage());
        }
    }

    /**
     * @param Underframe $underframe
     */
    private function manageEmbeddedImageAdmins(Underframe $underframe)
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
                $image = $underframe->$getter();

                if ($image) {
                    if ($image->getFile()) {
                        // update the Image to trigger file management
                        $image->refreshUpdated()
                            ->setCreatedAt(new \DateTime())
                            ->setEntityName($underframe::IMAGE_PATH);
                    } elseif (!$image->getFile() && !$image->getFilename()) {
                        // prevent Sf/Sonata trying to create and persist an empty Image
                        $underframe->$setter(null);
                    }
                }
            }
        }
    }
}
