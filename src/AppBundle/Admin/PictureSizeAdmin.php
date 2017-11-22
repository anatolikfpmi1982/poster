<?php

namespace AppBundle\Admin;

use AppBundle\Entity\PictureSize;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class PictureSizeAdmin extends AbstractAdmin
{
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Main')
            ->add('height', null, ['required' => true, 'label' => 'Высота'])
            ->add('width', null, ['required' => true, 'label' => 'Ширина'])
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
            ->add('height', null, ['required' => true, 'label' => 'Высота', 'editable' => true])
            ->add('width', null, ['required' => true, 'label' => 'Ширина', 'editable' => true])
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
            ->add('height', null, ['label' => 'Высота'])
            ->add('width', null, ['label' => 'Ширина'])
            ->add('isActive', null, ['label' => 'Показывать'])
        ;
    }

    /**
     * @param mixed $size
     */
    public function prePersist($size)
    {
        if($size instanceof PictureSize) {
            $size->setCreatedAt(new \DateTime());
            $size->setUpdatedAt(new \DateTime());
        }
    }

    /**
     * @param mixed $size
     */
    public function preUpdate($size)
    {
        if($size instanceof PictureSize) {
            $size->setUpdatedAt(new \DateTime());
        }
    }
}