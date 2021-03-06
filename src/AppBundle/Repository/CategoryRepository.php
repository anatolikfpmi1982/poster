<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Category3;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends \Doctrine\ORM\EntityRepository {

    const MAIN_CATEGORY_ID = 2;
    const MAIN_CATEGORY_TITLE = 'Модульные картины';

    /**
     * @return array
     */
    public function getRoot() {
        return $this->createQueryBuilder( 'category' )
                    ->where( 'category.isActive = :status' )
                    ->andWhere( 'category.parent_category IS NULL' )
                    ->orderBy( 'category.id', 'DESC' )
                    ->setParameter( 'status', true )
                    ->getQuery()
                    ->getResult();
    }

    /**
     * Get catalog tree.
     *
     * @param array $settings
     *
     * @return array
     */
    public function getCatalogMenu( $settings ) {
        $categories   = [ ];
        $dbCategories = $this->findBy( [ 'isActive' => true ], [ 'parent_category' => 'ASC', 'weight' => 'DESC' ] );
        if ( $dbCategories ) {
            foreach ( $dbCategories as $category ) {
                /** @var Category3 $category */
                $inner = [
                    'id'       => $category->getId(),
                    'clean_id' => $category->getId(),
                    'title'    => $category->getTitle(),
                    'slug'     => $category->getSlug(),
                ];

                $parent1 = $category->getParentCategory();
                $cause1  = $parent1 instanceof Category3 && $parent1->isIsActive();
                if ( $cause1 && ! $parent1->getParentCategory() ) {
                    $parentId = $category->getParentCategory()->getId();
                    if ( ! array_key_exists( $parentId, $categories ) ) {
                        $categories[ $parentId ]['children'] = [ ];
                    }

                    $categories[ $parentId ]['children'][ $category->getId() ]['id']       = $category->getId();
                    $categories[ $parentId ]['children'][ $category->getId() ]['clean_id'] = $category->getId();
                    $categories[ $parentId ]['children'][ $category->getId() ]['title']    = $category->getTitle();
                    $categories[ $parentId ]['children'][ $category->getId() ]['slug']     = $category->getSlug();

                } else if ( $cause1 && $parent1->getParentCategory() instanceof Category3
                            && $parent1->getParentCategory()->isIsActive() &&
                            $parent1->getParentCategory()->getParentCategory() === null
                ) {
                    $parentParentId = $parent1->getParentCategory()->getId();
                    $parentId       = $category->getParentCategory()->getId();
                    if ( ! array_key_exists( $parentParentId, $categories ) ) {
                        $categories[ $parentParentId ]['children'] = [ ];
                    }
                    if ( ! array_key_exists( 'children', $categories[ $parentParentId ] ) ||
                         ! array_key_exists( $parentId, $categories[ $parentParentId ]['children'] )
                    ) {
                        $categories[ $parentParentId ]['children'][ $parentId ]['children'] = [ ];
                    }

                    $categories[ $parentParentId ]['children'][ $parentId ]['children'][ $category->getId() ]['id']       = $category->getId();
                    $categories[ $parentParentId ]['children'][ $parentId ]['children'][ $category->getId() ]['clean_id'] = $category->getId();
                    $categories[ $parentParentId ]['children'][ $parentId ]['children'][ $category->getId() ]['title']    = $category->getTitle();
                    $categories[ $parentParentId ]['children'][ $parentId ]['children'][ $category->getId() ]['slug']     = $category->getSlug();
                } else if ( $cause1 && $parent1->getParentCategory() instanceof Category3
                            && $parent1->getParentCategory()->isIsActive() && $parent1->getParentCategory()->getParentCategory() instanceof Category3
                            && $parent1->getParentCategory()->getParentCategory()->isIsActive() &&
                            $parent1->getParentCategory()->getParentCategory()->getParentCategory() === null
                ) {
                    $parentParentParentId = $parent1->getParentCategory()->getParentCategory()->getId();
                    $parentParentId       = $parent1->getParentCategory()->getId();
                    $parentId             = $category->getParentCategory()->getId();
                    if ( ! array_key_exists( $parentParentParentId, $categories ) ) {
                        $categories[ $parentParentParentId ]['children'] = [ ];
                    }
                    if ( ! array_key_exists( 'children', $categories[ $parentParentParentId ] ) ||
                         ! array_key_exists( $parentParentId, $categories[ $parentParentParentId ]['children'] )
                    ) {
                        $categories[ $parentParentParentId ]['children'][ $parentParentId ]['children'] = [ ];
                    }
                    if ( ! array_key_exists( 'children', $categories[ $parentParentParentId ] ) ||
                         ! array_key_exists( $parentParentId, $categories[ $parentParentParentId ]['children'] ) ||
                         ! array_key_exists( 'children', $categories[ $parentParentParentId ]['children'][ $parentParentId ] ) ||
                         ! array_key_exists( $parentId, $categories[ $parentParentParentId ]['children'][ $parentParentId ]['children'] )
                    ) {
                        $categories[ $parentParentParentId ]['children'][ $parentParentId ]['children'][ $parentId ]['children'] = [ ];
                    }

                    $categories[ $parentParentParentId ]['children'][ $parentParentId ]['children'][ $parentId ]['children'][ $category->getId() ]['id']
                                                       = $category->getId();
                    $categories[ $parentParentParentId ]['children'][ $parentParentId ]['children'][ $parentId ]['children']
                    [ $category->getId() ]['clean_id'] = $category->getId();
                    $categories[ $parentParentParentId ]['children'][ $parentParentId ]['children'][ $parentId ]['children'][ $category->getId() ]['title']
                                                       = $category->getTitle();
                    $categories[ $parentParentParentId ]['children'][ $parentParentId ]['children'][ $parentId ]['children'][ $category->getId() ]['slug']
                                                       = $category->getSlug();
                } else if ( $parent1 === null ) {
                    if ( array_key_exists( $category->getId(), $categories ) ) {
                        $categories[ $category->getId() ]['id']       = $inner['id'];
                        $categories[ $category->getId() ]['clean_id'] = $inner['clean_id'];
                        $categories[ $category->getId() ]['title']    = $inner['title'];
                        $categories[ $category->getId() ]['slug']     = $inner['slug'];
                    } else {
                        $categories[ $category->getId() ] = $inner;
                    }

                }
            }
        }

        if ( array_key_exists( 'enable_module', $settings ) && $settings['enable_module'] ) {
            $categories = $this->createModule( $categories );
        }

        return $categories;
    }

    /**
     * Create module type menu categories.
     *
     * @param array $categories
     *
     * @return array
     */
    protected function createModule( $categories ) {
        if ( array_key_exists( self::MAIN_CATEGORY_ID, $categories ) ) {
            $categories[ 'm' . self::MAIN_CATEGORY_ID ] = $this->applyModuleIdRecursive( $categories[ self::MAIN_CATEGORY_ID ] );
        }

        return $categories;
    }

    /**
     * Recursive apply prefix for id in categories menu.
     *
     * @param array $array
     * @param bool $isRecursive
     *
     * @return array
     */
    protected function applyModuleIdRecursive( $array, $isRecursive = false ) {
        if ( is_array( $array ) && array_key_exists( 'id', $array ) ) {
            $array['id'] = 'm' . $array['id'];
            if ( ! $isRecursive ) {
                $array['title'] = self::MAIN_CATEGORY_TITLE;
            }
            if ( array_key_exists( 'children', $array ) && is_array( $array['children'] ) ) {
                foreach ( $array['children'] as $key => $value ) {
                    $array['children'][ 'm' . $key ] = $this->applyModuleIdRecursive( $value, true );
                    unset( $array['children'][ $key ] );
                }
            }
        }

        return $array;
    }
}
