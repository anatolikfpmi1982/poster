<?php
namespace AppBundle\Admin;

use AppBundle\Entity\PictureForm;
use AppBundle\Entity\Image;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use AppBundle\Service\ImageManagement;

/**
 * Admin class for picture form
 */
class PictureFormAdmin extends AbstractAdmin
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
            ->add('name', null, ['required' => true, 'label' => 'Название'])
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
            ->add('image', null,
                ['label' => 'Изображение', 'template' => 'AppBundle:Admin:list_image.html.twig'])
            ->add('name', null, ['editable'=> true, 'label' => 'Название'])
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
     * @param mixed $pictureForm
     */
    public function prePersist($pictureForm)
    {
        if($pictureForm instanceof PictureForm) {
            $pictureForm->setCreatedAt(new \DateTime());
            $pictureForm->setUpdatedAt(new \DateTime());
            $this->manageEmbeddedImageAdmins($pictureForm);
        }
    }

    /**
     * @param mixed $pictureForm
     */
    public function preUpdate($pictureForm)
    {
        if($pictureForm instanceof PictureForm) {
            $pictureForm->setUpdatedAt(new \DateTime());
            $this->manageEmbeddedImageAdmins($pictureForm);
        }
    }

    /**
     * @param mixed $pictureForm
     */
    public function postUpdate($pictureForm)
    {
        if($pictureForm instanceof PictureForm) {
            $this->imageManagement->cleanGarbageImages();
        }
    }

    /**
     * @param mixed $pictureForm
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function preRemove($pictureForm){
        if($pictureForm instanceof PictureForm) {
            $this->imageManagement->deleteImage($pictureForm->getImage());
        }
    }

    /**
     * @param PictureForm $pictureForm
     */
    private function manageEmbeddedImageAdmins(PictureForm $pictureForm)
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
                $image = $pictureForm->$getter();

                if ($image) {
                    if ($image->getFile()) {
                        // update the Image to trigger file management
                        $image->refreshUpdated()
                            ->setCreatedAt(new \DateTime())
                            ->setEntityName($pictureForm::IMAGE_PATH);
                    } elseif (!$image->getFile() && !$image->getFilename()) {
                        // prevent Sf/Sonata trying to create and persist an empty Image
                        $pictureForm->$setter(null);
                    }
                }
            }
        }
    }
}
