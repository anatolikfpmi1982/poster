<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Category;
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

    public function getFormBuilder()
    {
        // NEXT_MAJOR: set constraints unconditionally
        if (isset($this->formOptions['cascade_validation'])) {
            unset($this->formOptions['cascade_validation']);
            $this->formOptions['constraints'][] = new Valid();
        } else {
            @trigger_error(<<<'EOT'
Unsetting cascade_validation is deprecated since 3.2, and will give an error in 4.0.
Override getFormBuilder() and remove the "Valid" constraint instead.
EOT
                , E_USER_DEPRECATED);
        }

        return parent::getFormBuilder();
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General', array('class' => 'col-md-6'))
            ->add('name')
            ->add('description',
                // NEXT_MAJOR: remove when dropping Symfony <2.8 support
                method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')
                    ? 'Symfony\Component\Form\Extension\Core\Type\TextareaType'
                    : 'textarea',
                array(
                    'required' => false,
                )
            )
        ;

        if ($this->hasSubject()) {
            if ($this->getSubject()->getParent() !== null || $this->getSubject()->getId() === null) { // root category cannot have a parent
                $formMapper
                    ->add('parent',
                        // NEXT_MAJOR: remove when dropping Symfony <2.8 support
                        method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')
                            ? 'Sonata\ClassificationBundle\Form\Type\CategorySelectorType'
                            : 'sonata_category_selector',
                        array(
                            'category' => $this->getSubject() ?: null,
                            'model_manager' => $this->getModelManager(),
                            'class' => $this->getClass(),
                            'required' => true,
                            'context' => $this->getSubject()->getContext(),
                        )
                    )
                ;
            }
        }

        $position = $this->hasSubject() && !is_null($this->getSubject()->getPosition()) ? $this->getSubject()->getPosition() : 0;

        $formMapper
            ->end()
            ->with('Options', array('class' => 'col-md-6'))
            ->add('enabled', null, array(
                'required' => false,
            ))
            ->add('position',
                // NEXT_MAJOR: remove when dropping Symfony <2.8 support
                method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')
                    ? 'Symfony\Component\Form\Extension\Core\Type\IntegerType'
                    : 'integer',
                array(
                    'required' => false,
                    'data' => $position,
                )
            )
            ->end()
        ;

    }



//    /**
//     * @param FormMapper $formMapper
//     */
//    protected function configureFormFields(FormMapper $formMapper)
//    {
//        $formMapper
//            ->with('Main')
//            ->add('name', null, ['required' => true, 'label' => 'Название'])
//            ->add('slug', null, ['required' => false, 'label' => 'URL'])
//            ->add('parent', null,
//                ['required' => false, 'label' => 'Родительская категория'])
//            ->add('enabled', null, ['required' => false, 'label' => 'Показывать'])
//            ->end();
//    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id', null, ['required' => true, 'label' => 'ID'])
            ->add('name', null, ['required' => true, 'label' => 'Название', 'editable' => true])
            ->add('slug', null, ['required' => false, 'label' => 'Алиас'])
//            ->add('createdAt', null, ['label' => 'Создано'])
//            ->add('updatedAt', null, ['label' => 'Обновлено'])
            ->add('enabled', null, ['label' => 'Показывать', 'editable' => true])
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
            ->add('name', null, ['label' => 'Название'])
            ->add('enabled', null, ['label' => 'Показывать'])
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