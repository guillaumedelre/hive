<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 13/03/16
 * Time: 11:48
 */

namespace CoreBundle\Entity\Repository;


use CoreBundle\Entity\AbstractEntity;
use CoreBundle\Entity\Category;
use CoreBundle\Entity\Document;
use CoreBundle\Entity\Hive;

class DocumentRepository extends AbstractRepository
{
    /**
     * @param Hive $hive
     * @param integer $offset
     * @return Document[]
     */
    public function getByHive(Hive $hive, $offset = 0)
    {
        return $this->createQueryBuilder('d')
            ->innerJoin('d.user', 'u')
            ->innerJoin('u.hive', 'h')
            ->where('u.hive = :hive')
            ->setParameter('hive', $hive)
            ->setMaxResults(AbstractEntity::DEFAULT_LIMIT_APP)
            ->setFirstResult($offset)
            ->orderBy('d.updatedAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param Hive $hive
     * @return Document[]
     */
    public function getAllByHive(Hive $hive)
    {
        return $this->createQueryBuilder('d')
            ->innerJoin('d.user', 'u')
            ->innerJoin('u.hive', 'h')
            ->where('u.hive = :hive')
            ->setParameter('hive', $hive)
            ->orderBy('d.updatedAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param Hive $hive
     * @param integer $offset
     * @return Document[]
     */
    public function getByHiveAndCategory(Hive $hive, Category $category, $offset = 0)
    {
        return $this->createQueryBuilder('d')
            ->innerJoin('d.user', 'u')
            ->innerJoin('u.hive', 'h')
            ->where('u.hive = :hive')
            ->andWhere('d.category = :category')
            ->setParameter('hive', $hive)
            ->setParameter('category', $category)
            ->setMaxResults(AbstractEntity::DEFAULT_LIMIT_APP)
            ->setFirstResult($offset)
            ->orderBy('d.updatedAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param Hive $hive
     * @param integer $offset
     * @return Document[]
     */
    public function getAllByHiveAndCategory(Hive $hive, Category $category)
    {
        return $this->createQueryBuilder('d')
            ->innerJoin('d.user', 'u')
            ->innerJoin('u.hive', 'h')
            ->where('u.hive = :hive')
            ->andWhere('d.category = :category')
            ->setParameter('hive', $hive)
            ->setParameter('category', $category)
            ->orderBy('d.updatedAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
}