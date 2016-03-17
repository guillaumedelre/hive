<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 13/03/16
 * Time: 11:48
 */

namespace CoreBundle\Entity\Repository;


use CoreBundle\Entity\AbstractEntity;
use CoreBundle\Entity\Event;

class EventRepository extends AbstractRepository
{
    const TYPE_VOTE  = 'vote';
    const TYPE_EVENT = 'event';

    public function getMonthEvents()
    {
        $firstDayOfMonth = date('d/m/Y ',(strtotime('first day of this month')));
        $lastDayOfMonth  = date('d/m/Y ',(strtotime('last day of this month')));

        return $this->createQueryBuilder('e')
            ->where('e.type = :type')
            ->andWhere('e.startAt <= :monthStart')
            ->andWhere('e.endAt >= :monthEnd')
            ->setParameters(array(
                'type' => self::TYPE_EVENT,
                'monthStart' => $firstDayOfMonth,
                'monthEnd' => $lastDayOfMonth,
            ))
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Event[]
     */
    public function getFinishedVoteEvents($offset)
    {
        return $this->createQueryBuilder('e')
            ->where('e.type = :type')
            ->andWhere('e.endAt < :endAt')
            ->setParameters(array(
                'type' => self::TYPE_VOTE,
                'endAt' => new \DateTime(),
            ))
            ->setMaxResults(AbstractEntity::DEFAULT_LIMIT_APP)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Event[]
     */
    public function getAllFinishedVoteEvents()
    {
        return $this->createQueryBuilder('e')
            ->where('e.type = :type')
            ->andWhere('e.endAt < :endAt')
            ->setParameters(array(
                'type' => self::TYPE_VOTE,
                'endAt' => new \DateTime(),
            ))
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Event[]
     */
    public function getCurrentVoteEvents($offset)
    {
        return $this->createQueryBuilder('e')
            ->where('e.type = :type')
            ->andWhere('e.startAt <= :now')
            ->andWhere('e.endAt >= :now')
            ->setParameters(array(
                'type' => self::TYPE_VOTE,
                'now' => new \DateTime(),
            ))
            ->setMaxResults(AbstractEntity::DEFAULT_LIMIT_APP)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Event[]
     */
    public function getAllCurrentVoteEvents()
    {
        return $this->createQueryBuilder('e')
            ->where('e.type = :type')
            ->andWhere('e.startAt <= :now')
            ->andWhere('e.endAt >= :now')
            ->setParameters(array(
                'type' => self::TYPE_VOTE,
                'now' => new \DateTime(),
            ))
            ->getQuery()
            ->getResult()
        ;
    }
}