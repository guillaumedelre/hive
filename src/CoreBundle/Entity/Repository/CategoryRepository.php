<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 13/03/16
 * Time: 11:48
 */

namespace CoreBundle\Entity\Repository;


use CoreBundle\Entity\Category;
use CoreBundle\Entity\Hive;

class CategoryRepository extends AbstractRepository
{
    /**
     * @param Hive $hive
     * @return Category[]
     */
    public function getAllByHive(Hive $hive)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.user', 'u')
            ->innerJoin('u.hive', 'h')
            ->where('u.hive = :hive')
            ->setParameter('hive', $hive)
            ->orderBy('c.label', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
}