<?php

namespace AppBundle\Sonata;

use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery as SonataProxyQuery;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Doctrine\DBAL\Types\Type;

class ProxyQuery extends  SonataProxyQuery
{
    protected function getFixedQueryBuilder(QueryBuilder $queryBuilder)
    {
        $queryBuilderId = clone $queryBuilder;
        $rootAlias = current($queryBuilderId->getRootAliases());

        // step 1 : retrieve the targeted class
        $from = $queryBuilderId->getDQLPart('from');
        $class = $from[0]->getFrom();
        $metadata = $queryBuilderId->getEntityManager()->getMetadataFactory()->getMetadataFor($class);

        // step 2 : retrieve identifier columns
        $idNames = $metadata->getIdentifierFieldNames();

        // step 3 : retrieve the different subjects ids
        $selects = array();
        $idxSelect = '';
        foreach ($idNames as $idName) {
            $select = sprintf('%s.%s', $rootAlias, $idName);
            // Put the ID select on this array to use it on results QB
            $selects[$idName] = $select;
            // Use IDENTITY if id is a relation too.
            // See: http://doctrine-orm.readthedocs.org/en/latest/reference/dql-doctrine-query-language.html
            // Should work only with doctrine/orm: ~2.2
            $idSelect = $select;
            if ($metadata->hasAssociation($idName)) {
                $idSelect = sprintf('IDENTITY(%s) as %s', $idSelect, $idName);
            }
            $idxSelect .= ($idxSelect !== '' ? ', ' : '').$idSelect;
        }
        $queryBuilderId->resetDQLPart('select');
        $queryBuilderId->add('select', $idxSelect);

        // for SELECT DISTINCT, ORDER BY expressions must appear in idxSelect list
        /* Consider
            SELECT DISTINCT x FROM tab ORDER BY y;
        For any particular x-value in the table there might be many different y
        values.  Which one will you use to sort that x-value in the output?
        */
        // todo : check how doctrine behave, potential SQL injection here ...
        if ($this->getSortBy()) {
            $sortBy = $this->getSortBy();
            if (strpos($sortBy, '.') === false) { // add the current alias
                $sortBy = $rootAlias.'.'.$sortBy;
            }
            $sortBy .= ' AS __order_by';
            $queryBuilderId->addSelect($sortBy);
        }

        $results = $queryBuilderId->getQuery()->execute(array(), Query::HYDRATE_ARRAY);
        $platform = $queryBuilderId->getEntityManager()->getConnection()->getDatabasePlatform();
        $idxMatrix = array();
        foreach ($results as $id) {
            foreach ($idNames as $idName) {
                // Convert ids to database value in case of custom type, if provided.
                $fieldType = $metadata->getTypeOfField($idName);
                $idxMatrix[$idName][] = $fieldType && Type::hasType($fieldType)
                    ? Type::getType($fieldType)->convertToDatabaseValue($id[$idName], $platform)
                    : $id[$idName];
            }
        }

        // step 4 : alter the query to match the targeted ids
        foreach ($idxMatrix as $idName => $idx) {
            if (count($idx) > 0) {
                $idxParamName = sprintf('%s_idx', $idName);
                $idxParamName = preg_replace('/[^\w]+/', '_', $idxParamName);
                $queryBuilder->andWhere(sprintf('%s IN (:%s)', $selects[$idName], $idxParamName));
                $queryBuilder->setParameter($idxParamName, $idx);
                $queryBuilder->setMaxResults(null);
                $queryBuilder->setFirstResult(null);
            }
        }

        return $queryBuilder;
    }
}