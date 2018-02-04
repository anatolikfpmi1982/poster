<?php
namespace AppBundle\Sonata;

use Sonata\DoctrineORMAdminBundle\Datagrid\Pager as SonataPager;

class Pager extends SonataPager
{
    public function computeNbResult()
    {
        $countQuery = clone $this->getQuery();

        if (count($this->getParameters()) > 0) {
            $countQuery->setParameters($this->getParameters());
        }

        $countQuery->select(sprintf(
            'count(DISTINCT %s.%s) as cnt',
            $countQuery->getRootAlias(),
            current($this->getCountColumn())
        ));

        $result =  $countQuery->resetDQLPart('orderBy')->getQuery()->getScalarResult();
        return count($result) == 1 ? $result[0]['cnt'] : count($result);
    }
}