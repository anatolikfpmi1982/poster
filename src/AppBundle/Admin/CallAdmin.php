<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Call;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CallAdmin extends AbstractAdmin
{
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Main')
            ->add('name', null, ['required' => true, 'label' => 'Имя'])
            ->add('phone', null, ['required' => true, 'label' => 'Телефон'])
            ->add('isDone', null, ['required' => false, 'label' => 'Выполнено'])
            ->end();
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id', null, ['required' => true, 'label' => 'ID'])
            ->add('name', null, ['required' => true, 'label' => 'Имя'])
            ->add('phone', null, ['required' => true, 'label' => 'Телефон'])
//            ->add('createdAt', null, ['label' => 'Создано'])
//            ->add('updatedAt', null, ['label' => 'Обновлено'])
            ->add('isDone', null, ['label' => 'Выполнено'])
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
            ->add('name', null, ['label' => 'Имя'])
            ->add('phone', null, ['label' => 'Телефон'])
            ->add('isDone', null, ['label' => 'Выполнено'])
        ;
    }

    /**
     * @param mixed $call
     */
    public function prePersist($call)
    {
        if($call instanceof Call) {
            $call->setCreatedAt(new \DateTime());
            $call->setUpdatedAt(new \DateTime());
        }
    }

    /**
     * @param mixed $call
     */
    public function preUpdate($call)
    {
        if($call instanceof Call) {
            $call->setUpdatedAt(new \DateTime());
        }
    }
}