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
use CoreBundle\Entity\User;
use FOS\RestBundle\Util\Codes;

class EventRepository extends AbstractRepository
{
    const TYPE_VOTE  = 'vote';
    const TYPE_EVENT = 'event';

    /**
     * @param null $from
     * @param null $to
     * @param $limit
     * @param $offset
     * @return array
     * @throws \Exception
     */
    public function getEventsBetween($from = null, $to = null, $limit, $offset)
    {
        if (null === $from || null === $to) {
            throw new \Exception('Missing parameter', Codes::HTTP_BAD_REQUEST);
        }

        $from = (new \DateTime)->setTimestamp($from/1000);
        $to   = (new \DateTime)->setTimestamp($to/1000);

        return $this->createQueryBuilder('e')
            ->where('e.startAt <= :from AND e.endAt >= :from AND e.endAt <= :to')
            ->orWhere('e.startAt >= :from AND e.startAt <= :to AND e.endAt >= :to')
            ->orWhere('e.startAt >= :from AND e.endAt <= :to')
            ->andWhere('e.type = :type')
            ->setParameters(array(
                'type'  => EventRepository::TYPE_EVENT,
                'from'  => $from->format('Y-m-d'),
                'to'    => $to->format('Y-m-d'),
            ))
            ->setMaxResults($limit)
            ->setFirstResult($offset)
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
     * @param Event $event
     * @param User $me
     * @return float
     */
    public function getCurrentContributionByEvent(Event $event, User $me)
    {
        try {
            $result = ceil(count($me->getHive()->getUsers()) * 100 / count($event->getVotes()));
        } catch (\Exception $e) {
            $result = 0;
        }

        return $result;
    }

    /**
     * @param Event $event
     * @param User $me
     * @return bool
     */
    public function userHasContributed(Event $event, User $me)
    {
        foreach($event->getVotes() as $vote) {
            foreach($me->getVotes() as $myVote) {
                if ($myVote === $vote) {
                    return true;
                }
            }
        }

        return false;
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