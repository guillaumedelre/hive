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
        switch($event->getType()) {
            case EventRepository::TYPE_EVENT:
                $class = 'event-info';
                break;
            case EventRepository::TYPE_VOTE:
                $class = 'event-special';
                break;
        }
        $calEvent = new CalendarEvent();
        $calEvent->setId($event->getId());
        $calEvent->setTitle($event->getTitle());
        $calEvent->setSlug($event->getSlug());
        $calEvent->setDescription($event->getDescription());
        $calEvent->setClass($class);
        $calEvent->setUrl($this->router->getGenerator()->generate('app_default_index', ['hiveSlug' => $this->me->getUser()->getHive()->getSlug()]));
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