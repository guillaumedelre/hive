<?php

namespace CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * User
 *
 * @ORM\Table(name="user", indexes={@ORM\Index(name="fk_user_uid1_idx", columns={"uid"})})
 * @ORM\Entity
 */
class User extends AbstractEntity
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
     * @var string
     *
     * @ORM\Column(name="uid", type="string", length=255, nullable=false)
     */
    private $uid;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64, nullable=false)
     */
    private $password;

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
     * @return string
     */
    public function __toString()
    {
        return strval($this->uid);
    }

}

