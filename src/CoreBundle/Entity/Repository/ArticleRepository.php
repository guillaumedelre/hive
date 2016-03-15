<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 13/03/16
 * Time: 11:48
 */

namespace CoreBundle\Entity\Repository;


use CoreBundle\Entity\AbstractEntity;
use CoreBundle\Entity\Article;
use CoreBundle\Entity\Category;
use CoreBundle\Entity\Hive;

class ArticleRepository extends AbstractRepository
{
    /**
     * @param Hive $hive
     * @param integer $offset
     * @return Article[]
     */
    public function getByHive(Hive $hive, $offset = 0)
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.author', 'u')
            ->innerJoin('u.hive', 'h')
            ->where('u.hive = :hive')
            ->setParameter('hive', $hive)
            ->setMaxResults(AbstractEntity::DEFAULT_LIMIT_APP)
            ->setFirstResult($offset)
            ->orderBy('a.updatedAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param Hive $hive
     * @return Article[]
     */
    public function getAllByHive(Hive $hive)
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.author', 'u')
            ->innerJoin('u.hive', 'h')
            ->where('u.hive = :hive')
            ->setParameter('hive', $hive)
            ->orderBy('a.updatedAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param Hive $hive
     * @param integer $offset
     * @return Article[]
     */
    public function getByHiveAndCategory(Hive $hive, Category $category, $offset = 0)
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.author', 'u')
            ->innerJoin('u.hive', 'h')
            ->where('u.hive = :hive')
            ->andWhere('a.category = :category')
            ->setParameter('hive', $hive)
            ->setParameter('category', $category)
            ->setMaxResults(AbstractEntity::DEFAULT_LIMIT_APP)
            ->setFirstResult($offset)
            ->orderBy('a.updatedAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param Hive $hive
     * @param integer $offset
     * @return Article[]
     */
    public function getAllByHiveAndCategory(Hive $hive, Category $category)
    {
        return $this->createQueryBuilder('a')
            ->innerJoin('a.author', 'u')
            ->innerJoin('u.hive', 'h')
            ->where('u.hive = :hive')
            ->andWhere('a.category = :category')
            ->setParameter('hive', $hive)
            ->setParameter('category', $category)
            ->orderBy('a.updatedAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

}