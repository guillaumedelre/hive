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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Document", mappedBy="category")
     */
    private $documents;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Article", mappedBy="category")
     */
    private $articles;

    /**
     * @return string
     */
    public function __toString()
    {
        return strval($this->label);
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->documents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set label
     *
     * @param string $label
     *
     * @return Category
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Category
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Category
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
     * @return Category
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
     * Add document
     *
     * @param \CoreBundle\Entity\Document $document
     *
     * @return Category
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

    /**
     * Add article
     *
     * @param \CoreBundle\Entity\Article $article
     *
     * @return Category
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
}
