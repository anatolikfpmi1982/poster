<?php

namespace AppBundle\Admin;

use AppBundle\Entity\MainMenu;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Doctrine\ORM\EntityManager;
use AppBundle\Service\ImageManagement;

class MainMenuAdmin extends AbstractAdmin
{
    /**
     * @var EntityManager
     */
    protected $em;

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
     * @param EntityManager $entityManager
     * @param ImageManagement $imageManagement
     */
    public function __construct(
        $code,
        $class,
        $baseControllerName,
        EntityManager $entityManager,
        ImageManagement $imageManagement
    ) {
        parent::__construct($code, $class, $baseControllerName);
        $this->em = $entityManager;
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
            ->add('type', 'sonata_type_choice_field_mask', array(
                'choices' => array(
                    'page' => 'Страница',
                    'category' => 'Категория',
                    'picture' => 'Картина',
                    'target' => 'Ссылка',
                ),
                'map' => array(
                    'page' => array('page'),
                    'category' => array('category'),
                    'picture' => array('picture'),
                    'target' => array('target'),
                ),
                'placeholder' => 'Выберите тип ссылки',
                'required' => false
            ))
            ->add('page', null, ['required' => false, 'label' => 'Выберите страницу'])
            ->add('category', null, ['required' => false, 'label' => 'Выберите категорию'])
            ->add('picture', null, ['required' => false, 'label' => 'Выберите картину'])
            ->add('target', null, ['required' => false, 'label' => 'Укажите ссылку'])
            ->add('weight', null, ['required' => false, 'label' => 'Вес', 'empty_data' => '0', 'attr' => ['placeholder' => 0]])
            ->add('image', 'sonata_type_admin', ['required' => false, 'label' => 'Изображение'])
            ->add('isActive', null, ['required' => false, 'label' => 'Показывать'])
            ->end();
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $pagesChoices = [];
        $pages = $this->em->getRepository('AppBundle\Entity\Page')->findBy(['isActive' => true]);
        if($pages) {
            foreach ($pages as $v) {
                $pagesChoices[$v->getId()] = (string)$v;
            }
        }

        $listMapper
            ->add('id', null, ['label' => 'ID'])
            ->add('title', null, ['label' => 'Название', 'editable' => true])
            ->add('image', null,
                ['label' => 'Изображение', 'template' => 'AppBundle:Admin:list_image.html.twig'])
            ->add('type', null,
                ['label' => 'Материал', 'template' => 'AppBundle:Admin:main_menu_list_material.html.twig'])
            ->add('target', null, ['label' => 'Ссылка'])
            ->add('weight', null, ['label' => 'Вес', 'editable' => true])
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
            ->add('target', null, ['label' => 'Ссылка'])
            ->add('isActive', null, ['label' => 'Показывать'])
        ;
    }

    /**
     * @param mixed $menu
     */
    public function prePersist($menu)
    {
        if($menu instanceof MainMenu) {
            $menu = $this->clearEntities($menu);
            $menu->setCreatedAt(new \DateTime());
            $menu->setUpdatedAt(new \DateTime());
            $this->manageEmbeddedImageAdmins($menu);
        }
    }

    /**
     * @param mixed $menu
     */
    public function preUpdate($menu)
    {
        if($menu instanceof MainMenu) {
            $menu = $this->clearEntities($menu);
            $menu->setUpdatedAt(new \DateTime());
            $this->manageEmbeddedImageAdmins($menu);
        }
    }

    /**
     * @param MainMenu $menu
     */
    public function postUpdate($menu)
    {
        if($menu instanceof MainMenu) {
            $this->imageManagement->cleanGarbageImages();
        }
    }

    /**
     * Delete entities dependencies
     *
     * @param MainMenu $menu
     * @return MainMenu
     */
    private function clearEntities(MainMenu $menu) {
        if($menu->getType() != 'page') {
            $menu->setPage(null);
        }
        if($menu->getType() != 'category') {
            $menu->setCategory(null);
        }
        if($menu->getType() != 'picture') {
            $menu->setPicture(null);
        }

        return $menu;
    }

    /**
     * @param MainMenu $menu
     */
    private function manageEmbeddedImageAdmins(MainMenu $menu)
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
                $image = $menu->$getter();

                if ($image) {
                    if ($image->getFile()) {
                        // update the Image to trigger file management
                        $image->refreshUpdated()
                            ->setCreatedAt(new \DateTime())
                            ->setEntityName($menu::IMAGE_PATH);
                    } elseif (!$image->getFile() && !$image->getFilename()) {
                        // prevent Sf/Sonata trying to create and persist an empty Image
                        $menu->$setter(null);
                    }
                }
            }
        }
    }
}