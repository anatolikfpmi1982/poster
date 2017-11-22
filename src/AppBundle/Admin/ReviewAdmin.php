<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Review;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class ReviewAdmin extends AbstractAdmin
{
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Main')
            ->add('name', null, ['required' => true, 'label' => 'ФИО'])
            ->add('review', null, ['required' => true, 'label' => 'Отзыв'])
            ->add('city', null, ['required' => true, 'label' => 'Город'])
            ->add('email', null, ['required' => true, 'label' => 'Email'])
            ->add('slug', null, ['required' => false, 'label' => 'URL'])
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
            ->add('name', null, ['required' => true, 'label' => 'ФИО'])
            ->add('review', null, ['required' => true, 'label' => 'Отзыв'])
            ->add('city', null, ['required' => true, 'label' => 'Город'])
            ->add('email', null, ['required' => true, 'label' => 'Email'])
            ->add('slug', null, ['required' => false, 'label' => 'URL'])
            ->add('createdAt', null, ['label' => 'Создано'])
            ->add('updatedAt', null, ['label' => 'Обновлено'])
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
            ->add('name', null, ['label' => 'ФИО'])
            ->add('city', null, ['label' => 'Город'])
            ->add('email', null, ['label' => 'Email'])
            ->add('isActive', null, ['label' => 'Показывать'])
        ;
    }

    /**
     * @param mixed $review
     */
    public function prePersist($review)
    {
        if($review instanceof Review) {
            $review->setCreatedAt(new \DateTime());
            $review->setUpdatedAt(new \DateTime());
        }
    }

    /**
     * @param mixed $review
     */
    public function preUpdate($review)
    {
        if($review instanceof Review) {
            $review->setUpdatedAt(new \DateTime());
        }
    }
}