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
            ->add('image', 'sonata_type_admin', ['required' => false, 'label' => 'Изображение'])
            ->add('imageBanner', 'sonata_type_admin', ['required' => true, 'label' => 'Изображение-баннер'])
            ->add('imageModule', 'sonata_type_admin', ['required' => true, 'label' => 'Изображение-модульное'])
            ->add('imageFrame', 'sonata_type_admin', ['required' => true, 'label' => 'Изображение-рама'])
//            ->add('code', null, ['required' => true, 'label' => 'Артикул'])
            ->add('id', null, ['required' => false, 'label' => 'ID', 'disabled' =>true])
            ->add('code', null, ['required' => false, 'label' => 'Артикул', 'disabled' =>true])
            ->add('title', null, ['required' => true, 'label' => 'Название'])
            ->add('body', CKEditorType::class, ['required' => true, 'label' => 'Текст'])
            ->add('slug', null, ['required' => false, 'label' => 'Алиас'])
            ->add('author', null, ['required' => false, 'label' => 'Автор'])
            ->add('type', null, ['required' => false, 'label' => 'Арт (Фото, если не выбрано)'])
            ->add('price', null, ['required' => false, 'label' => 'Цена', 'empty_data' => '0', 'attr' => ['placeholder' => 0]])
            ->add('ratio', null, ['required' => false, 'label' => 'Коэффициент', 'empty_data' => '1', 'attr' => ['placeholder' => 1]])
            ->add('categories', null, ['required' => false, 'label' => 'Категории'])
            ->add('similar', null, ['required' => false, 'label' => 'Похожие картины'])
            ->add('colors', null, ['required' => false, 'label' => 'Цвет картины'])
            ->add('form', null, ['required' => false, 'label' => 'Форма картины'])
            ->add('isTop', null, ['required' => false, 'label' => 'Топ'])
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

        $categoriesChoices = [];
        $categories = $this->em->getRepository('AppBundle\Entity\Category3')->findBy(['isActive' => true]);
        if($categories) {
            foreach ($categories as $v) {
                $categoriesChoices[$v->getId()] = (string)$v;
            }
        }

        $listMapper
            ->add('id', null, ['label' => 'ID'])
            ->add('code', null, ['label' => 'Артикул'])
            ->add('image', null,
                ['label' => 'Изображение', 'template' => 'AppBundle:Admin:pictures_list_image.html.twig'])
            ->add('title', null, ['label' => 'Название', 'editable' => true])
            ->add('slug', null, ['label' => 'Алиас', 'editable' => true])
            ->add('author', 'choice', ['label' => 'Автор','editable' => true,
                'class' => 'Appbundle\Entity\Author', 'choices' => $authorsChoices, 'sortable' => true,
                'sort_field_mapping'=> ['fieldName'=>'id'], 'sort_parent_association_mappings' => [['fieldName'=>'author']]])
            ->add('type', null, ['label' => 'Арт', 'editable' => true])
            ->add('price', null, ['label' => 'Цена', 'editable' => true,
                'template' => 'AppBundle:Admin:list_field_float_editable.html.twig'])
            ->add('ratio', null, ['label' => 'Коэффициент', 'editable' => true,
                'template' => 'AppBundle:Admin:list_field_float_editable.html.twig'])
            ->add('categories', null, ['label' => 'Категории', 'editable' => true, 'sortable' => true,
                'sort_field_mapping'=> ['fieldName'=>'id'], 'sort_parent_association_mappings' => [['fieldName'=>'categories']]])
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
                        'generate' => ['template' => 'AppBundle:Admin:list__action_generate.html.twig']
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
            ->add('code', null, ['label' => 'Артикул'])
            ->add('author', null, ['label' => 'Автор'])
            ->add('type', null, ['label' => 'Арт/Фото'])
            ->add('price', null, ['label' => 'Цена'])
            ->add('ratio', null, ['label' => 'Коэффициент'])
            ->add('colors', null, ['label' => 'Цвет'])
            ->add('form', null, ['label' => 'Форма'])
            ->add('categories', null, ['label' => 'Категории'], null, ['multiple' => true])
            ->add('isActive', null, ['label' => 'Показывать'])
        ;
    }

    /**
     * Configure admin
     */
    public function configure() {
        $this->setTemplate('edit', 'AppBundle:Admin:edit_javascript.html.twig');
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
     * @throws \Doctrine\ORM\OptimisticLockException
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
     * @throws \Doctrine\ORM\OptimisticLockException
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