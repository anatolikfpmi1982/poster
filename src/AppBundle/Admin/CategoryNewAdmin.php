<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Category;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Doctrine\ORM\EntityManager;

class CategoryNewAdmin extends AbstractAdmin
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
            ->add('description', CKEditorType::class, ['required' => false, 'label' => 'Описание'])
            ->add('seoTitle', null, ['required' => false, 'label' => 'SEO - Title'])
            ->add('seoDescription', null, ['required' => false, 'label' => 'SEO - Description'])
            ->add('seoKeywords', null, ['required' => false, 'label' => 'SEO - Keywords'])
            ->add('slug', null, ['required' => false, 'label' => 'Алиас'])
            ->add('parent_category', null, ['required' => false, 'label' => 'Родительская категория'])
            ->add('tags', null, ['required' => false, 'label' => 'Теги'])
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
            ->add('slug', null, ['label' => 'Алиас'])
//            ->add('createdAt', null, ['label' => 'Создано'])
//            ->add('updatedAt', null, ['label' => 'Обновлено'])
            ->add('parent_category', null, ['label' => 'Родительская категория'])
            ->add('tags', null, ['label' => 'Теги'])
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
            ->add('parent_category', null, ['label' => 'Родительская категория'])
            ->add('tags', null, ['label' => 'Тег'])
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