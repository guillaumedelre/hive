<?php

namespace CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="user", indexes={@ORM\Index(name="fk_user_username1_idx", columns={"username"})})
 * @ORM\Entity
 * @Serializer\XmlRoot("user")
 * @Hateoas\Relation("self", href = "expr('/api/users/' ~ object.getId())")
 * @Hateoas\Relation(
 *     "hive",
 *     href = "expr('/api/hives/' ~ object.getHive().getId())",
 *     embedded = "expr(object.getHive())",
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(object.getHive() === null)")
 * )
 */
class User extends AbstractEntity implements UserInterface
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
     * @ORM\Column(type="string", length=32, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64, nullable=false)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=64, nullable=true)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=64, nullable=false)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", length=10, nullable=true)
     */
    private $phoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="cell_number", type="string", length=10, nullable=true)
     */
    private $cellNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=2, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="zip_code", type="string", length=6, nullable=true)
     */
    private $zipCode;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true)
     */
    private $avatar;

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
     * @var Hive
     *
     * @ORM\ManyToOne(targetEntity="Hive", inversedBy="users", fetch="EAGER"))
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="hive_id", referencedColumnName="id")
     * })
     */
    private $hive;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Article", mappedBy="author")
     */
    private $articles;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Vote", mappedBy="user")
     */
    private $votes;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Event", mappedBy="user")
     */
    private $events;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Document", mappedBy="user")
     */
    private $documents;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->isActive = true;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return strval($this->username);
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
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
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return User
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set cellNumber
     *
     * @param string $cellNumber
     *
     * @return User
     */
    public function setCellNumber($cellNumber)
    {
        $this->cellNumber = $cellNumber;

        return $this;
    }

    /**
     * Get cellNumber
     *
     * @return string
     */
    public function getCellNumber()
    {
        return $this->cellNumber;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set zipCode
     *
     * @param string $zipCode
     *
     * @return User
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * Get zipCode
     *
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return User
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
     * @return User
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
     * Set hive
     *
     * @param \CoreBundle\Entity\Hive $hive
     *
     * @return User
     */
    public function setHive(\CoreBundle\Entity\Hive $hive = null)
    {
        $this->hive = $hive;

        return $this;
    }

    /**
     * Get hive
     *
     * @return \CoreBundle\Entity\Hive
     */
    public function getHive()
    {
        return $this->hive;
    }

    /**
     * Add article
     *
     * @param \CoreBundle\Entity\Article $article
     *
     * @return User
     */
    public function addArticle(\CoreBundle\Entity\Article $article)
    {
        $this->articles[] = $article;

        return $this;
    }

    /**
     * Remove article
     *
     * @param \CoreBundle\Entity\Article $article
     */
    public function removeArticle(\CoreBundle\Entity\Article $article)
    {
        $this->articles->removeElement($article);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * Add vote
     *
     * @param \CoreBundle\Entity\Vote $vote
     *
     * @return User
     */
    public function addVote(\CoreBundle\Entity\Vote $vote)
    {
        $this->votes[] = $vote;

        return $this;
    }

    /**
     * Remove vote
     *
     * @param \CoreBundle\Entity\Vote $vote
     */
    public function removeVote(\CoreBundle\Entity\Vote $vote)
    {
        $this->votes->removeElement($vote);
    }

    /**
     * Get votes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * Add event
     *
     * @param \CoreBundle\Entity\Event $event
     *
     * @return User
     */
    public function addEvent(\CoreBundle\Entity\Event $event)
    {
        $this->events[] = $event;

        return $this;
    }

    /**
     * Remove event
     *
     * @param \CoreBundle\Entity\Event $event
     */
    public function removeEvent(\CoreBundle\Entity\Event $event)
    {
        $this->events->removeElement($event);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Add document
     *
     * @param \CoreBundle\Entity\Document $document
     *
     * @return User
     */
    public function addDocument(\CoreBundle\Entity\Document $document)
    {
        $this->documents[] = $document;

        return $this;
    }

    /**
     * Remove document
     *
     * @param \CoreBundle\Entity\Document $document
     */
    public function removeDocument(\CoreBundle\Entity\Document $document)
    {
        $this->documents->removeElement($document);
    }

    /**
     * Get documents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocuments()
    {
        return $this->documents;
    }
}
