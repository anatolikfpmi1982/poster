<?php
namespace AppBundle\Admin;

use AppBundle\Entity\Underframe;
use AppBundle\Entity\Image;
use Doctrine\Common\Collections\ArrayCollection;
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
            ->add('depth', null, ['required' => false, 'label' => 'Толщина'])
            ->add('price', null, ['required' => false, 'label' => 'Цена'])
            ->add('ratio', null, ['required' => false, 'label' => 'Коэффициент'])
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
     * @param mixed $moduleType
     */
    public function prePersist($moduleType)
    {
        if($moduleType instanceof Underframe) {
            $moduleType->setCreatedAt(new \DateTime());
            $moduleType->setUpdatedAt(new \DateTime());
            $this->manageEmbeddedImageAdmins($moduleType);
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
            $this->imageManagement->deleteImages($underframe->getImages());
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
            if ($fieldDescription->getType() === 'sonata_type_collection' &&
                ($associationMapping = $fieldDescription->getAssociationMapping()) &&
                $associationMapping['targetEntity'] === 'AppBundle\Entity\Image'
            ) {
                $getter = 'get'.$fieldName;
                $setter = 'set'.$fieldName;

                /** @var ArrayCollection $image */
                $images = $underframe->$getter();

                if (count($images)>0) {
                    foreach($images as $image) {
                        /** @var Image $image */;
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
}
