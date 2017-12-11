<?php
namespace AppBundle\Sonata;

use Sonata\DoctrineORMAdminBundle\Datagrid\Pager as SonataPager;

class Pager extends SonataPager
{
    public function computeNbResult()
    {
        return $this->getPage()*$this->getMaxPerPage()+1;
    }
}