<?php

namespace CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity
 * @Serializer\XmlRoot("category")
 * @Hateoas\Relation("self", href = "expr('/api/categories/' ~ object.getId())")
 * @Hateoas\Relation(
 *     "parent",
 *     href = "expr('/api/categories/' ~ object.getParent().getId())",
 *     embedded = "expr(object.getParent())",
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(object.getParent() === null)")
 * )
 */
class Category extends AbstractEntity
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
     * @ORM\Column(name="label", type="string", length=64, nullable=false)
     */
    private $label;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"label"}, updatable=true, separator="-")
     * @ORM\Column(name="slug", type="string", length=255, nullable=false)
     */
    private $slug;

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
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children", fetch="EAGER"))
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * })
     */
    private $parent;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     */
    private $children;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Document", mappedBy="category")
     */
    private $documents;

    /**
     * @return string
     */
    public function __toString()
    {
        return strval($this->label);
    }
}

