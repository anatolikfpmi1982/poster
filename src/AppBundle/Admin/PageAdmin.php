<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Page;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class PageAdmin extends AbstractAdmin
{
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Main')
            ->add('title', null, ['required' => true, 'label' => 'Название'])
            ->add('body', CKEditorType::class, ['required' => true, 'label' => 'Текст'])
            ->add('seoTitle', null, ['required' => false, 'label' => 'SEO - Title'])
            ->add('seoDescription', null, ['required' => false, 'label' => 'SEO - Description'])
            ->add('seoKeywords', null, ['required' => false, 'label' => 'SEO - Keywords'])
            ->add('slug', null, ['required' => false, 'label' => 'Алиас'])
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
            ->add('slug', null, ['required' => false, 'label' => 'Алиас'])
            ->add('seoTitle', null, ['required' => true, 'label' => 'SEO - Title'])
            ->add('seoDescription', null, ['required' => true, 'label' => 'SEO - Description'])
            ->add('seoKeywords', null, ['required' => true, 'label' => 'SEO - Keywords'])
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
            ->add('isActive', null, ['label' => 'Показать'])
        ;
    }

    /**
     * @param mixed $page
     */
    public function prePersist($page)
    {
        if($page instanceof Page) {
            $page->setCreatedAt(new \DateTime());
            $page->setUpdatedAt(new \DateTime());
        }
    }

    /**
     * @param mixed $page
     */
    public function preUpdate($page)
    {
        if($page instanceof Page) {
            $page->setUpdatedAt(new \DateTime());
        }
    }
}