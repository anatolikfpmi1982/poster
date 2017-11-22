<?php

namespace AppBundle\Admin;

use AppBundle\Entity\FrameMaterial;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class FrameMaterialAdmin extends AbstractAdmin
{
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Main')
            ->add('title', null, ['required' => true, 'label' => 'Название'])
            ->add('isActive', null, ['required' => true, 'label' => 'Показывать', 'editable' => true])
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

//    public function getBatchActions()
//    {
//        $actions = parent::getBatchActions();
//
//        if ($this->hasRoute('edit')) {
//
//            $actions['test'] = array(
//                'label'            => 'Test',
//                'ask_confirmation' => false
//            );
//        }
//
//        return $actions;
//    }
//
//    public function getTemplate($name)
//    {
//        switch ($name) {
//            case 'list':
//                return 'AppBundle:FrameMaterial:list.html.twig';
//                break;
//
//            default:
//                return parent::getTemplate($name);
//                break;
//        }
//    }

    /**
     * @param mixed $material
     */
    public function prePersist($material)
    {
        if($material instanceof FrameMaterial) {
            $material->setCreatedAt(new \DateTime());
            $material->setUpdatedAt(new \DateTime());
        }
    }

    /**
     * @param mixed $material
     */
    public function preUpdate($material)
    {
        if($material instanceof FrameMaterial) {
            $material->setUpdatedAt(new \DateTime());
        }
    }
}