<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Image;
use AppBundle\Entity\Picture;
use AppBundle\Service\ImageManagement;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PictureAdmin extends AbstractAdmin
{
    /**
     * @var array
     */
    protected $perPageOptions = array(16, 32, 64, 128, 192, 384);

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
            ->add('categories', null, ['required' => false, 'label' => 'Категории'])
            ->add('image', 'sonata_type_admin', ['required' => true, 'label' => 'Изображение'])
            ->add('id', null, ['required' => false, 'label' => 'ID', 'disabled' => true])
            ->add('code', null, ['required' => false, 'label' => 'Артикул', 'disabled' => true])
            ->add('title', null, ['required' => false, 'label' => 'Название'])
            ->add('body', null, ['required' => false, 'label' => 'Описание'])
            ->add('slug', null, ['required' => false, 'label' => 'Алиас'])
            ->add('author', null, ['required' => false, 'label' => 'Автор'])
            ->add('type', null, ['required' => false, 'label' => 'Арт (Фото, если не выбрано)'])
            ->add('note', null, ['required' => false, 'label' => 'Примечание'])
            ->add('price', null, ['required' => false, 'label' => 'Цена', 'empty_data' => '0', 'attr' => ['placeholder' => 0]])
            ->add('ratio', null, ['required' => false, 'label' => 'Коэффициент', 'empty_data' => '1', 'attr' => ['placeholder' => 1]])
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

        $listMapper
            ->add('id', null, ['label' => 'ID'])
            ->add('code', null, ['label' => 'Артикул'])
            ->add('image', null,
                ['label' => 'Изображение', 'template' => 'AppBundle:Admin:pictures_list_image.html.twig'])
            ->add('name', null, ['label' => 'Имя файла', 'editable' => true])
            ->add('title', null, ['label' => 'Название', 'editable' => true])
            ->add('note', null, ['label' => 'Примечание', 'editable' => true])
            ->add('author', 'choice', ['label' => 'Автор','editable' => true,
                'class' => 'Appbundle\Entity\Author', 'choices' => $authorsChoices, 'sortable' => true,
                'sort_field_mapping'=> ['fieldName'=>'id'], 'sort_parent_association_mappings' => [['fieldName'=>'author']]])
            ->add('type', null, ['label' => 'Арт', 'editable' => true])
            ->add('price', null, ['label' => 'Цена', 'editable' => true,
                'template' => 'AppBundle:Admin:list_field_float_editable.html.twig'])
            ->add('ratio', null, ['label' => 'Коэффициент', 'editable' => true,
                'template' => 'AppBundle:Admin:list_field_float_editable.html.twig'])
            ->add('body', 'html', ['label' => 'Описание', 'editable' => true, 'template' => 'AppBundle:Admin:picture_description_list.html.twig'])
            ->add('categories', null, ['label' => 'Категории', 'editable' => true, 'sortable' => true,
                'sort_field_mapping'=> ['fieldName'=>'id'], 'sort_parent_association_mappings' => [['fieldName'=>'categories']]])
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
            ->add('isTop', null, ['label' => 'Топ', 'sortable' => true, 'editable' => true])
            ->add('createdAt', null, ['label' => 'Создано'])
            ->add('updatedAt', null, ['label' => 'Обновлено'])
            ->add('slug', null, ['label' => 'Алиас', 'editable' => true, 'template' => 'AppBundle:Admin:picture_slug_list.html.twig']);

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
            ->add('title', null, ['label' => 'Название'])
            ->add('note', null, ['label' => 'Примечание'])
            ->add('body', null, ['label' => 'Описание'])
            ->add('code', null, ['label' => 'Артикул'])
            ->add('name', null, ['label' => 'Имя файла'])
            ->add('author', null, ['label' => 'Автор'])
            ->add('type', null, ['label' => 'Арт/Фото'])
            ->add('price', null, ['label' => 'Цена'])
            ->add('ratio', null, ['label' => 'Коэффициент'])
            ->add('colors', null, ['label' => 'Цвет'])
            ->add('form', null, ['label' => 'Форма'])
//            ->add('categories', 'doctrine_orm_callback', [
//                'label' => 'Категории',
////                'multiple' => true,
//                'callback'   => [$this, 'changeFilerForCategories']
//            ], null, ['multiple' => true])
            ->add('categories', 'doctrine_orm_callback', [
                'label' => 'Категории',
                'callback'   => [$this, 'changeFilerForCategories'],
                'field_type' => 'choice',
                'field_options' => array(
                    'choices' => $categoriesChoices,
                    'required' => false,
                    'multiple' => true,
                    'attr' => array('size' => 7),
                ),
            ])
            ->add('isActive', null, ['label' => 'Показывать'])
            ->add('isTop', null, ['label' => 'Топ'])
            ->add('createdAt', 'doctrine_orm_date_range', ['label' => 'Создано'])
            ->add('updatedAt', 'doctrine_orm_date_range', ['label' => 'Обновлено'])
        ;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $alias
     * @param string $field
     * @param string|array $value
     *
     * @return bool|void
     */
    public function changeFilerForCategories($queryBuilder, $alias, $field, $value)
    {
        if (!$value['value']) {
            return null;
        }

        $queryBuilder->join(sprintf('%s.categories', $alias), 'p');
        $queryBuilder->andWhere('p.id IN ('. implode(',', $value['value']) .')');
        $queryBuilder->addGroupBy(sprintf('%s.id', $alias));
        $queryBuilder->having('COUNT(DISTINCT p.id) = ' . count($value['value']));
        return true;
    }

    /**
     * Configure admin
     */
    public function configure() {
        $this->setTemplate('edit', 'AppBundle:Admin:edit_javascript.html.twig');
    }

    public function getTemplate($name)
    {
        switch ($name) {
            case 'list':
                return 'AppBundle:Categories:list.html.twig';
                break;

            default:
                return parent::getTemplate($name);
                break;
        }
    }

    public function getBatchActions()
    {
        $actions = parent::getBatchActions();

        if ($this->hasRoute('edit') && $this->isGranted('EDIT')) {
            $actions['change_title'] = array(
                'label'            => 'Изменить название',
                'ask_confirmation' => false
            );
            $actions['change_description'] = array(
                'label'            => 'Изменить описание',
                'ask_confirmation' => false
            );
            $actions['change_note'] = array(
                'label'            => 'Изменить примечание',
                'ask_confirmation' => false
            );
            $actions['change_author'] = array(
                'label'            => 'Изменить автора',
                'ask_confirmation' => false
            );
            $actions['change_art'] = array(
                'label'            => 'Изменить арт/фото',
                'ask_confirmation' => false
            );
            $actions['change_price'] = array(
                'label'            => 'Изменить цену',
                'ask_confirmation' => false
            );
            $actions['change_ratio'] = array(
                'label'            => 'Изменить коэффициент',
                'ask_confirmation' => false
            );
            $actions['change_show'] = array(
                'label'            => 'Изменить доступность на сайте',
                'ask_confirmation' => false
            );
            $actions['change_top'] = array(
                'label'            => 'Задать топы',
                'ask_confirmation' => false
            );
            $actions['add_category'] = array(
                'label'            => 'Добавить категорию',
                'ask_confirmation' => false
            );
            $actions['delete_category'] = array(
                'label'            => 'Удалить категорию',
                'ask_confirmation' => false
            );
        }

        return $actions;
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
            if(!$picture->getName() && $picture->getImage() instanceof Image)  {
                $picture->setName( $picture->getImage()->getName() );
            }
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
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postUpdate($picture)
    {
        if($picture instanceof Picture) {
            if(!$picture->getName() && $picture->getImage() instanceof Image) {
                $picture->setName( $picture->getImage()->getName() );
                $this->em->persist($picture);
                $this->em->flush();
            }
            $this->imageManagement->cleanGarbageImages();
        }
    }

    /**
     * @param mixed $picture
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function preRemove($picture){
        if($picture instanceof Picture) {
            $this->imageManagement->deleteImages($picture->getImage());
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