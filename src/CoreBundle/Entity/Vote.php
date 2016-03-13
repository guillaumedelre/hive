<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * Vote
 *
 * @ORM\Table(name="vote")
 * @ORM\Entity
 * @Serializer\XmlRoot("vote")
 * @Hateoas\Relation("self", href = "expr('/api/votes/' ~ object.getId())")
 * @Hateoas\Relation(
 *     "event",
 *     href = "expr('/api/events/' ~ object.getEvent().getId())",
 *     embedded = "expr(object.getEvent())",
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(object.getEvent() === null)")
 * )
 * @Hateoas\Relation(
 *     "user",
 *     href = "expr('/api/users/' ~ object.getUser().getId())",
 *     embedded = "expr(object.getUser())",
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(object.getUser() === null)")
 * )
 */
class Vote extends AbstractEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="votes", fetch="EAGER"))
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var Event
     *
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="votes", fetch="EAGER"))
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     * })
     */
    private $event;

    /**
     * @ORM\Column(name="approved", type="boolean")
     */
    private $approved;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    protected $updatedAt;

    /**
     * @return string
     */
    public function __toString()
    {
        return strval($this->id);
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Vote
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Vote
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set user
     *
     * @param \CoreBundle\Entity\User $user
     *
     * @return Vote
     */
    public function setUser(\CoreBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \CoreBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set event
     *
     * @param \CoreBundle\Entity\Event $event
     *
     * @return Vote
     */
    public function setEvent(\CoreBundle\Entity\Event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \CoreBundle\Entity\Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Get approved
     *
     * @return mixed
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * Set approved
     *
     * @param mixed $approved
     *
     * @return Vote
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;

        return $this;
    }
}
