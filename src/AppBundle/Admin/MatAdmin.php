<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Mat;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class MatAdmin extends AbstractAdmin
{
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Main')
            ->add('title', null, ['required' => true, 'label' => 'Название'])
            ->add('color', null, ['required' => true, 'label' => 'Цвет'])
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
            ->add('title', null, ['required' => true, 'label' => 'Название', 'editable' => true])
            ->add('color', null, ['required' => true, 'label' => 'Цвет', 'editable' => true])
//            ->add('createdAt', null, ['label' => 'Создано'])
//            ->add('updatedAt', null, ['label' => 'Опубликовано'])
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
            ->add('color', null, ['label' => 'Цвет'])
            ->add('isActive', null, ['label' => 'Показывать'])
        ;
    }

    /**
     * @param mixed $mat
     */
    public function prePersist($mat)
    {
        if($mat instanceof Mat) {
            $mat->setCreatedAt(new \DateTime());
            $mat->setUpdatedAt(new \DateTime());
        }
    }

    /**
     * @param mixed $mat
     */
    public function preUpdate($mat)
    {
        if($mat instanceof Mat) {
            $mat->setUpdatedAt(new \DateTime());
        }
    }
}