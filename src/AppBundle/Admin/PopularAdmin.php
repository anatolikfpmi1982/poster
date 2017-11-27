<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Picture;
use AppBundle\Entity\Popular;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PopularAdmin extends AbstractAdmin
{
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Main')
            ->add('title', null, ['required' => true, 'label' => 'Заголовок'])
            ->add('slug', null, ['required' => true, 'label' => 'СЛАГ'])
            ->add(
                'pictures',
                'sonata_type_model',
                [
                    'required' => false,
                    'label' => 'Картины',
                    'by_reference' => false,
                    'multiple' => true
                ]
//                ,
//                [
//                    'edit' => 'inline',
//                    'inline' => 'table',
//                    'sortable' => 'id',
//                    'limit' => 10
//                ]
            )
            ->end();
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id', null, ['required' => true, 'label' => 'ID'])
            ->add('title', null, ['required' => true, 'label' => 'Заголовок', 'editable' => true])
            ->add('slug', null, ['required' => true, 'label' => 'СЛАГ', 'editable' => true])
            ->add(
                '_action',
                'actions',
                [
                    'actions' => [
                        'edit' => [],
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
            ->add('title', null, ['label' => 'Заголовок'])
            ->add('slug', null, ['label' => 'СЛАГ'])
        ;
    }

    /**
     * @param mixed $popular
     */
    public function prePersist($popular)
    {
        if($popular instanceof Popular) {
            $popular->setCreatedAt(new \DateTime());
            $popular->setUpdatedAt(new \DateTime());
            $pictures = $popular->getPictures();
            foreach($pictures as $picture) {
                /** @var Picture $picture */
                $picture->setPopular($popular);
            }
        }
    }

    /**
     * @param mixed $popular
     */
    public function preUpdate($popular)
    {
        if($popular instanceof Popular) {
            $popular->setUpdatedAt(new \DateTime());
            $pictures = $popular->getPictures();
            foreach($pictures as $picture) {
                /** @var Picture $picture */
                $picture->setPopular($popular);
            }
        }
    }
}