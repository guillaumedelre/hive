<?php
/**
 * Created by PhpStorm.
 * User: guillaume
 * Date: 18/03/16
 * Time: 10:04
 */

namespace CoreBundle\Entity;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class CalendarEvent
 * @package ApiBundle\Entity
 * @Serializer\XmlRoot("calendarEvent")
 */
class CalendarEvent
{
    const STATUS_IN_PROGRESS = 'IN PROGRESS';
    const STATUS_PAST        = 'PAST';
    const STATUS_INCOMING    = 'INCOMING';

    /** @var integer $id */
    protected $id;
    /** @var string $title  */
    protected $title;
    /** @var string $slug */
    protected $slug;
    /** @var string $description */
    protected $description;
    /** @var string $url */
    protected $url;
    /** @var string $class */
    protected $class;
    /** @var integer $start */
    protected $start;
    /** @var integer $end */
    protected $end;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return CalendarEvent
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return CalendarEvent
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return CalendarEvent
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return CalendarEvent
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return CalendarEvent
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     * @return CalendarEvent
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param int $start
     * @return CalendarEvent
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * @return int
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param int $end
     * @return CalendarEvent
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

}