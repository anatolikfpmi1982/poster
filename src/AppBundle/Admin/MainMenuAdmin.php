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
            ->add('target', null, ['required' => true, 'label' => 'Ссылка'])
            ->add('weight', null, ['required' => true, 'label' => 'Вес'])
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
            ->add('title', null, ['required' => true, 'label' => 'Название'])
            ->add('target', null, ['required' => true, 'label' => 'Ссылка'])
            ->add('weight', null, ['required' => true, 'label' => 'Вес'])
//            ->add('createdAt', null, ['label' => 'Создано'])
//            ->add('updatedAt', null, ['label' => 'Обновлено'])
            ->add('isActive', null, ['label' => 'Показывать'])
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
        }
    }

    /**
     * @param mixed $menu
     */
    public function preUpdate($menu)
    {
        if($menu instanceof MainMenu) {
            $menu->setUpdatedAt(new \DateTime());
        }
    }
}