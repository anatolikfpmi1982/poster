<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Image;
use AppBundle\Entity\Picture;
use Doctrine\ORM\EntityManager;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use AppBundle\Service\ImageManagement;

class PictureAdmin extends AbstractAdmin
{
    /**
     * @var ImageManagement
     */
    protected $imageManagement;

    /**
     * @var
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
            ->add('image', 'sonata_type_admin', ['required' => false, 'label' => 'Изображение'])
            ->add('imageBanner', 'sonata_type_admin', ['required' => true, 'label' => 'Изображение-баннер'])
            ->add('imageModule', 'sonata_type_admin', ['required' => true, 'label' => 'Изображение-модульное'])
            ->add('imageFrame', 'sonata_type_admin', ['required' => true, 'label' => 'Изображение-рама'])
//            ->add('code', null, ['required' => true, 'label' => 'Артикул'])
            ->add('title', null, ['required' => true, 'label' => 'Название'])
            ->add('body', CKEditorType::class, ['required' => true, 'label' => 'Текст'])
            ->add('slug', null, ['required' => false, 'label' => 'Slug'])
            ->add('author', null, ['required' => false, 'label' => 'Автор'])
            ->add('type', null, ['required' => true, 'label' => 'Арт (Фото, если не выбрано)'])
            ->add('price', null, ['required' => true, 'label' => 'Цена'])
            ->add('ratio', null, ['required' => true, 'label' => 'Коэффициент'])
            ->add('categories', null, ['required' => false, 'label' => 'Категории'])
            ->add('isActive', null, ['required' => false, 'label' => 'Показывать'])
            ->end();
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id', null, ['required' => true, 'label' => 'ID'])
            ->add('image', null,
                ['label' => 'Изображение', 'template' => 'AppBundle:Admin:pictures_list_image.html.twig'])
//            ->addIdentifier('code', null, ['required' => true, 'label' => 'Артикул'])
            ->add('title', null, ['required' => true, 'label' => 'Название', 'editable' => true])
            ->add('slug', null, ['required' => false, 'label' => 'Slug', 'editable' => true])
            ->add('author', null, ['required' => false, 'label' => 'Автор'])
            ->add('type', null, ['required' => true, 'label' => 'Арт', 'editable' => true])
            ->add('price', null, ['required' => true, 'label' => 'Цена', 'editable' => true])
            ->add('ratio', null, ['required' => true, 'label' => 'Коэффициент', 'editable' => true])
            ->add('categories', null, ['required' => false, 'label' => 'Категории', 'editable' => true])
//            ->add('createdAt', null, ['label' => 'Создано'])
//            ->add('updatedAt', null, ['label' => 'Обновлено'])
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
            ->add('author', null, ['label' => 'Автор'])
            ->add('type', null, ['label' => 'Арт/Фото'])
            ->add('price', null, ['label' => 'Цена'])
            ->add('ratio', null, ['label' => 'Коэффициент'])
            ->add('categories', null, ['label' => 'Категории'])
            ->add('isActive', null, ['label' => 'Показывать'])
        ;
    }

    /**
     * @param mixed $picture
     */
    public function prePersist($picture)
    {
        if($picture instanceof Picture) {
            $picture->setCreatedAt(new \DateTime());
            $picture->setUpdatedAt(new \DateTime());
            $this->manageEmbeddedImageAdmins($picture);
            $picture->setCode('');
        }
    }

    /**
     * @param mixed $picture
     */
    public function postPersist($picture)
    {
        if($picture instanceof Picture) {
            $picture->setCode(100500 + $picture->getId());
            $this->em->persist($picture);
            $this->em->flush();
        }

    }


    /**
     * @param mixed $picture
     */
    public function preUpdate($picture)
    {
        if($picture instanceof Picture) {
            $picture->setUpdatedAt(new \DateTime());
            $this->manageEmbeddedImageAdmins($picture);
        }
    }

    /**
     * @param mixed $picture
     */
    public function postUpdate($picture)
    {
        if($picture instanceof Picture) {
            $this->imageManagement->cleanGarbageImages();
        }
    }

    /**
     * @param mixed $picture
     */
    public function preRemove($picture){
        if($picture instanceof Picture) {
            $this->imageManagement->deleteImages($picture->getImages());
        }
    }

    private function manageEmbeddedImageAdmins(Picture $picture)
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
                $image = $picture->$getter();

                if ($image) {
                    if ($image->getFile()) {
                        // update the Image to trigger file management
                        $image->refreshUpdated()
                            ->setCreatedAt(new \DateTime())
                            ->setEntityName($picture::IMAGE_PATH);
                    } elseif (!$image->getFile() && !$image->getFilename()) {
                        // prevent Sf/Sonata trying to create and persist an empty Image
                        $picture->$setter(null);
                    }
                }
            }
        }
    }
}