<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ReviewRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReviewRepository extends EntityRepository {

    const LATEST_OFFSET = 0;
    const LATEST_LIMIT = 10;

    /**
     * @return array
     */
    public function getLatestReviews() {
        return $this->createQueryBuilder( 'review' )
                    ->where( 'review.isActive = :isActive' )
                    ->orderBy( 'review.id', 'DESC' )
                    ->setFirstResult( self::LATEST_OFFSET )
                    ->setMaxResults( self::LATEST_LIMIT )
                    ->setParameter( 'isActive', true )
                    ->getQuery()
                    ->getResult();
    }
}