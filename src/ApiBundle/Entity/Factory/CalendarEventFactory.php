<?php
/**
 * Created by PhpStorm.
 * User: guillaume
 * Date: 18/03/16
 * Time: 10:07
 */

namespace ApiBundle\Entity\Factory;


use ApiBundle\Entity\CalendarEvent;
use CoreBundle\Entity\Event;
use CoreBundle\Entity\Repository\EventRepository;
use CoreBundle\Service\MeService;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class CalendarEventFactory
{
    /** @var Router $router */
    protected $router;

    /** @var MeService $me */
    protected $me;

    /**
     * CalendarEventFactory constructor.
     * @param Router $router
     */
    public function __construct(Router $router, MeService $me)
    {
        $this->router = $router;
        $this->me     = $me;
    }

    /**
     * @param Event $event
     * @return CalendarEvent
     */
    public function createFromEvent(Event $event)
    {
        $now = new \DateTime();

        $status = null;

        $event->setEndAt($event->getEndAt()->modify('+' . (3600 * 24 - 1) . ' seconds'));

        if ($now >= $event->getStartAt() && $now <= $event->getEndAt()) {
            $status = CalendarEvent::STATUS_IN_PROGRESS;
        } elseif ($now > $event->getEndAt()) {
            $status = CalendarEvent::STATUS_PAST;
        } elseif ($now < $event->getStartAt()) {
            $status = CalendarEvent::STATUS_INCOMING;
        }

        switch($status) {
            case CalendarEvent::STATUS_IN_PROGRESS:
                $class = 'event-success';
                break;
            case CalendarEvent::STATUS_INCOMING:
                $class = 'event-info';
                break;
            case CalendarEvent::STATUS_PAST:
                $class = 'event-important';
                break;
            default:
                $class = 'event';
                break;
        }

        $calEvent = new CalendarEvent();
        $calEvent->setId($event->getId());
        $calEvent->setTitle($event->getTitle());
        $calEvent->setSlug($event->getSlug());
        $calEvent->setDescription($event->getDescription());
        $calEvent->setClass($class);
        $calEvent->setUrl($this->router->getGenerator()->generate('app_event_read', ['hiveSlug' => $this->me->getUser()->getHive()->getSlug(), 'eventSlug' => $event->getSlug()]));
        $calEvent->setStart($event->getStartAt()->getTimestamp() * 1000);
        $calEvent->setEnd($event->getEndAt()->getTimestamp() * 1000);

        return $calEvent;
    }

    /**
     * @param Event[] $events
     * @return CalendarEvent[]
     */
    public function createFromEvents(array $events)
    {
        $result = [];

        foreach($events as $event) {
            $result[] = $this->createFromEvent($event);
        }

        return $result;
    }
}