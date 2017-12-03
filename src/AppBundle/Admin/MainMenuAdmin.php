<?php

namespace AppBundle\Admin;

use AppBundle\Entity\MainMenu;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class MainMenuAdmin extends AbstractAdmin
{
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Main')
            ->add('title', null, ['required' => true, 'label' => 'Название'])
            ->add('page', null, ['required' => false, 'label' => 'Выберите страницу'])
            ->add('target', null, ['required' => false, 'label' => 'или укажите ссылку'])
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
        $listMapper
            ->add('id', null, ['label' => 'ID'])
            ->add('title', null, ['label' => 'Название', 'editable' => true])
            ->add('image', null,
                ['label' => 'Изображение', 'template' => 'AppBundle:Admin:list_image.html.twig'])
            ->add('page', null, ['label' => 'Страница'])
            ->add('target', null, ['label' => 'Ссылка'])
            ->add('weight', null, ['label' => 'Вес', 'editable' => true])
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
            $menu->setUpdatedAt(new \DateTime());
            $this->manageEmbeddedImageAdmins($menu);
        }
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