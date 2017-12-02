<?php

namespace AppBundle\Admin;

use AppBundle\Entity\PictureColor;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class PictureColorAdmin extends AbstractAdmin
{
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Main')
            ->add('title', null, ['required' => true, 'label' => 'Название'])
            ->add('code', null, ['required' => true, 'label' => 'Код цвета (например FFFFFF)'])
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
            ->add('title', null, ['required' => true, 'label' => 'Название', 'editable' => true])
            ->add('code', null, ['required' => true, 'label' => 'Код цвета', 'editable' => true])
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
            ->add('isActive', null, ['label' => 'Показывать'])
        ;
    }

    /**
     * @param mixed $color
     */
    public function prePersist($color)
    {
        if($color instanceof FrameColor) {
            $color->setCreatedAt(new \DateTime());
            $color->setUpdatedAt(new \DateTime());
        }
    }

    /**
     * @param mixed $material
     */
    public function preUpdate($material)
    {
        if($material instanceof FrameColor) {
            $material->setUpdatedAt(new \DateTime());
        }
    }
}