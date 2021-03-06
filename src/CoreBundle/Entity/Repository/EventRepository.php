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
use CoreBundle\Entity\Vote;
use FOS\RestBundle\Util\Codes;

class EventRepository extends AbstractRepository
{
    const TYPE_VOTE  = 'vote';
    const TYPE_EVENT = 'event';

    /**
     * @param User $me
     * @param null $from
     * @param null $to
     * @param $limit
     * @param $offset
     * @return array
     * @throws \Exception
     */
    public function getEventsBetween(User $me, $from = null, $to = null, $limit, $offset)
    {
        if (null === $from || null === $to) {
            throw new \Exception('Missing parameter', Codes::HTTP_BAD_REQUEST);
        }

        $from = (new \DateTime)->setTimestamp($from/1000);
        $to   = (new \DateTime)->setTimestamp($to/1000);

        return $this->createQueryBuilder('e')
            ->where('e.user IN (:in)')
            ->andWhere('e.type = :type')
            ->andWhere('e.startAt <= :from AND e.endAt >= :from AND e.endAt <= :to')
            ->orWhere('e.startAt >= :from AND e.startAt <= :to AND e.endAt >= :to')
            ->orWhere('e.startAt >= :from AND e.endAt <= :to')
            ->setParameters(array(
                'in'    => $me->getHive()->getUsers(),
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
     * @param User $me
     * @param $offset
     * @return Event[]
     */
    public function getFinishedVoteEvents(User $me, $offset)
    {
        return $this->createQueryBuilder('e')
            ->where('e.user IN (:in)')
            ->andWhere('e.type = :type')
            ->andWhere('e.endAt < :endAt')
            ->setParameters(array(
                'in'    => $me->getHive()->getUsers(),
                'type'  => self::TYPE_VOTE,
                'endAt' => new \DateTime(),
            ))
            ->setMaxResults(AbstractEntity::DEFAULT_LIMIT_APP)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param User $me
     * @return Event[]
     */
    public function getAllFinishedVoteEvents(User $me)
    {
        return $this->createQueryBuilder('e')
            ->where('e.user IN (:in)')
            ->andWhere('e.type = :type')
            ->andWhere('e.endAt < :endAt')
            ->setParameters(array(
                'in'   => $me->getHive()->getUsers(),
                'type' => self::TYPE_VOTE,
                'endAt'=> new \DateTime(),
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
            $result = round(count($me->getHive()->getUsers()) * 100 / count($event->getVotes()));
        } catch (\Exception $e) {
            $result = 0;
        }

        return $result;
    }

    /**
     * @param Event $event
     * @param User $me
     * @return Vote|null
     */
    public function getUserContribution(Event $event, User $me)
    {
        /** @var Vote $myVote */
        foreach($me->getVotes() as $myVote) {
            if ($myVote->getEvent()->getId() === $event->getId()) {
                return $myVote;
            }
        }

        return null;
    }

    /**
     * @param User $me
     * @param $offset
     * @return Event[]
     */
    public function getCurrentVoteEvents(User $me, $offset)
    {
        return $this->createQueryBuilder('e')
            ->where('e.user IN (:in)')
            ->andWhere('e.type = :type')
            ->andWhere('e.startAt <= :now')
            ->andWhere('e.endAt >= :now')
            ->setParameters(array(
                'in'   => $me->getHive()->getUsers(),
                'type' => self::TYPE_VOTE,
                'now'  => new \DateTime(),
            ))
            ->setMaxResults(AbstractEntity::DEFAULT_LIMIT_APP)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param User $me
     * @return Event[]
     */
    public function getAllCurrentVoteEvents(User $me)
    {
        return $this->createQueryBuilder('e')
            ->where('e.user IN (:in)')
            ->andWhere('e.type = :type')
            ->andWhere('e.startAt <= :now')
            ->andWhere('e.endAt >= :now')
            ->setParameters(array(
                'in'   => $me->getHive()->getUsers(),
                'type' => self::TYPE_VOTE,
                'now'  => new \DateTime(),
            ))
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param Event $event
     * @return bool
     */
    public function isFinished(Event $event)
    {
        return $event->getEndAt() < new \DateTime();
    }

    /**
     * @param Event $event
     * @return string
     */
    public function getDivision(Event $event)
    {
        $approved = 0;
        $refused  = 0;

        /** @var Vote $vote */
        foreach($event->getVotes() as $vote) {
            if ($vote->getApproved()) {
                $approved++;
            } else {
                $refused++;
            }
        }

        $result = array(
            array(
                'value' => $approved,
                'color' => '#1bc98e',
                'label' => VoteRepository::LABEL_APPROVED,
            ),
            array(
                'value' => $refused,
                'color' => '#e64759',
                'label' => VoteRepository::LABEL_REFUSED,
            ),
        );

        return json_encode($result);
    }

    /**
     * @param User $me
     * @return Event[]
     */
    public function getCurrentEvents(User $me)
    {
        return $this->createQueryBuilder('e')
            ->where('e.user IN (:in)')
            ->andWhere('e.type = :type')
            ->andWhere('e.startAt <= :now')
            ->andWhere('e.endAt >= :now')
            ->setParameters(array(
                'in'   => $me->getHive()->getUsers(),
                'type' => self::TYPE_EVENT,
                'now'  => new \DateTime(),
            ))
            ->setMaxResults(AbstractEntity::DEFAULT_LIMIT_APP)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param User $me
     * @return Event[]
     */
    public function getIncomingEvents(User $me)
    {
        return $this->createQueryBuilder('e')
            ->where('e.user IN (:in)')
            ->andWhere('e.type = :type')
            ->andWhere('e.startAt > :now')
            ->setParameters(array(
                'type' => self::TYPE_EVENT,
                'in' => $me->getHive()->getUsers(),
                'now' => new \DateTime(),
            ))
            ->setMaxResults(AbstractEntity::DEFAULT_LIMIT_APP)
            ->getQuery()
            ->getResult()
            ;
    }
}