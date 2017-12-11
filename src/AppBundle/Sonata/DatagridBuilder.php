<?php

namespace AppBundle\Sonata;

use Sonata\DoctrineORMAdminBundle\Builder\DatagridBuilder as SonataDatagridBuilder;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\Datagrid;
use Symfony\Component\Form\FormFactory;

class DatagridBuilder extends SonataDatagridBuilder
{
    public function getBaseDatagrid(AdminInterface $admin, array $values = array())
    {
        $pager = new Pager;
        $pager->setCountColumn($admin->getModelManager()->getIdentifierFieldNames($admin->getClass()));

        $defaultOptions = array();
        if ($this->csrfTokenEnabled) {
            $defaultOptions['csrf_protection'] = false;
        }

        $formBuilder = $this->formFactory->createNamedBuilder('filter', 'form', array(), $defaultOptions);

        $originalProxyQuery = $admin->createQuery();
        $proxyQuery = new ProxyQuery($originalProxyQuery->getQueryBuilder());
        return new Datagrid($proxyQuery, $admin->getList(), $pager, $formBuilder, $values);
    }
}