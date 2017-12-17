<?php
namespace AppBundle\Admin;

use AppBundle\Entity\ParserError;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

/**
 * Admin class for parser errors
 */
class ParserErrorAdmin extends AbstractAdmin
{
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id', null, ['editable'=> false, 'label' => 'ID'])
            ->add('message', null, ['editable'=> true, 'label' => 'Ошибки'])
            ->add('createdAt', null, ['label' => 'Создано'])
//            ->add('updatedAt', null, ['label' => 'Обновлено'])
            ->add('isActive', null, ['editable' => true, 'label' => 'Просмотрено'])
            ->add(
                '_action',
                'actions',
                [
                    'actions' => [
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
            ->add('id', null, ['label' => 'ID'])
            ->add('isActive', null, ['label' => 'Просмотрено'])
        ;
    }

    /**
     * @param mixed $error
     */
    public function prePersist($error)
    {
        if($error instanceof ParserError) {
            $error->setCreatedAt(new \DateTime());
        }
    }
}
