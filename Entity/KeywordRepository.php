<?php

namespace Gore\BlogBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * KeywordRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class KeywordRepository extends EntityRepository
{
        
    /**
     * getAllCategories
     * Return all the categories on the blog
     * @return type
     */
    public function getAllCategories(){
        return $this->getAllKeywords(true);
    }
    
    
    
    /**
     * getAllKeywords
     * Get all keywords in database
     * @param type $category
     * @return type
     */
    public function getAllKeywords($category = false){
        $qb = $this->createQueryBuilder('k')
                   ->where('k.category = :categoryparam')
                   ->setParameter('categoryparam', $category)
                   ->orderBy('k.name', 'ASC');
        
        return $qb->getQuery()->getResult();
    }
    
    
    
    /**
     * getMostUsedKeywords
     * Get most used keywords on the blog
     * @param type $numberOfKeywords
     * @param type $includeCategories
     * @return type
     */
    public function getMostUsedKeywords($numberOfKeywords, $includeCategories = false){
        $qb = $this->createQueryBuilder('k')
                   ->select('k.id, k.name, count(k) as compteur')
                   ->groupBy('k.name')
                   ->orderBy('compteur', 'DESC')
                   ->setFirstResult(0)
                   ->setMaxResults($numberOfKeywords);
        
        if (!$includeCategories) $qb->where('k.category = false');
        
        return $qb->getQuery()->getResult();
    }

}
