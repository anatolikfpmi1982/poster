<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Author;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class AuthorAdmin extends AbstractAdmin
{
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Main')
            ->add('name', null, ['required' => true, 'label' => 'Имя'])
            ->add('isActive', null, ['required' => false, 'label' => 'Показывать'])
            ->end();
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name', null, ['required' => true, 'label' => 'Имя', 'editable' => true])
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
            ->add('name', null, ['label' => 'Имя'])
            ->add('isActive', null, ['label' => 'Показывать'])
        ;
    }

    /**
     * @param mixed $author
     */
    public function prePersist($author)
    {
        if($author instanceof Author) {
            $author->setCreatedAt(new \DateTime());
            $author->setUpdatedAt(new \DateTime());
        }
    }

    /**
     * @param mixed $author
     */
    public function preUpdate($author)
    {
        if($author instanceof Author) {
            $author->setUpdatedAt(new \DateTime());
        }
    }
}