<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 13/03/16
 * Time: 11:48
 */

namespace CoreBundle\Entity\Repository;


use CoreBundle\Entity\Event;

class EventRepository extends AbstractRepository
{
    const TYPE_VOTE  = 'vote';
    const TYPE_EVENT = 'event';

    /**
     * @return Event[]
     */
    public function getFinishedVoteEvents()
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
    public function getCurrentVoteEvents()
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