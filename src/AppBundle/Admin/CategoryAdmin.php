<?php

namespace AppBundle\Admin;

use Application\Sonata\ClassificationBundle\Entity\Category;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Doctrine\ORM\EntityManager;

class CategoryAdmin extends AbstractAdmin
{
    /**
     * @var
     */
    protected $em;

    /**
     * Constructor
     *
     * @param string $code
     * @param string $class
     * @param string $baseControllerName
     * @param EntityManager $entityManager
     */
    public function __construct(
        $code,
        $class,
        $baseControllerName,
        EntityManager $entityManager
    ) {
        parent::__construct($code, $class, $baseControllerName);
        $this->em = $entityManager;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Main')
            ->add('title', null, ['required' => true, 'label' => 'Название'])
            ->add('slug', null, ['required' => false, 'label' => 'URL'])
            ->add('parent_category', 'choice',
                ['choices' => $this->getCategoryTree(1), 'required' => false, 'label' => 'Родительская категория'])
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
            ->add('slug', null, ['required' => false, 'label' => 'Алиас'])
//            ->add('createdAt', null, ['label' => 'Создано'])
//            ->add('updatedAt', null, ['label' => 'Обновлено'])
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
     * @param mixed $category
     */
    public function prePersist($category)
    {
        if($category instanceof Category) {
            $category->setCreatedAt(new \DateTime());
            $category->setUpdatedAt(new \DateTime());
        }
    }

    /**
     * @param mixed $category
     */
    public function preUpdate($category)
    {
        if($category instanceof Category) {
            $category->setUpdatedAt(new \DateTime());
        }
    }

    /**
     * @param $id
     * @return array
     */
    private function getCategoryTree($id) {
        $qb = $this->em->createQueryBuilder();

        $qb = $qb->add('select', 'c')
            ->add('from', 'AppBundle\Entity\Category c');

        $query = $qb->getQuery();
        $arrayType = $query->getArrayResult();

//        foreach ($arrayType as $v) {
//            $result[$v['id']] =  $v['title'];
//        }
//        return $result;
        return $arrayType;
    }
}