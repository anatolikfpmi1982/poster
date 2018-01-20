<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Image;
use AppBundle\Entity\OwnPicture;
use AppBundle\Service\ImageManagement;
use Doctrine\ORM\EntityManager;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class OwnPictureAdmin extends AbstractAdmin
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
            ->add('name', null, ['required' => false, 'label' => 'Имя файла'])
            ->add('image', 'sonata_type_admin', ['required' => true, 'label' => 'Изображение'])
            ->add('isActive', null, ['required' => false, 'label' => 'Показывать'])
            ->end();
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $authorsChoices = [];
        $authors = $this->em->getRepository('AppBundle\Entity\Author')->findBy(['isActive' => true]);
        if($authors) {
            foreach ($authors as $v) {
                $authorsChoices[$v->getId()] = (string)$v;
            }
        }

        $listMapper
            ->add('id', null, ['label' => 'ID'])
            ->add('image', null,
                ['label' => 'Изображение', 'template' => 'AppBundle:Admin:pictures_list_image.html.twig'])
            ->add('name', null, ['label' => 'Имя файла', 'editable' => true])
            ->add('isActive', null, ['label' => 'Показывать', 'editable' => true])
            ->add(
                '_action',
                'actions',
                [
                    'actions' => [
                        'edit' => [],
                        'delete' => []
                    ],
                ]
            )
            ->add('createdAt', null, ['label' => 'Создано'])
            ->add('updatedAt', null, ['label' => 'Обновлено']);
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $categoriesChoices = [];
        $categories = $this->em->getRepository('AppBundle\Entity\Category3')->findBy(['isActive' => true]);
        if($categories) {
            foreach ($categories as $v) {
                $categoriesChoices[$v->getId()] = (string)$v;
            }
        }

        $datagridMapper
            ->add('name', null, ['label' => 'Имя файла'])
            ->add('isActive', null, ['label' => 'Показывать'])
            ->add('createdAt', 'doctrine_orm_date_range', ['label' => 'Создано'])
            ->add('updatedAt', 'doctrine_orm_date_range', ['label' => 'Обновлено'])
        ;
    }

    /**
     * @param mixed $picture
     */
    public function prePersist($picture)
    {
        if($picture instanceof OwnPicture) {
            $picture->setCreatedAt(new \DateTime());
            $picture->setUpdatedAt(new \DateTime());
            $this->manageEmbeddedImageAdmins($picture);
        }
    }

    /**
     * @param mixed $picture
     */
    public function preUpdate($picture)
    {
        if($picture instanceof OwnPicture) {
            $picture->setUpdatedAt(new \DateTime());
            $this->manageEmbeddedImageAdmins($picture);
        }
    }

    /**
     * @param mixed $picture
     */
    public function postUpdate($picture)
    {
        if($picture instanceof OwnPicture) {
            $this->imageManagement->cleanGarbageImages();
        }
    }

    /**
     * @param mixed $picture
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function preRemove($picture){
        if($picture instanceof OwnPicture) {
            $this->imageManagement->deleteImages($picture->getImage());
        }
    }

    private function manageEmbeddedImageAdmins(OwnPicture $picture)
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