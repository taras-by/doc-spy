<?php

namespace ParserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Source
 *
 * @ORM\Table(name="source")
 * @ORM\Entity(repositoryClass="ParserBundle\Repository\SourceRepository")
 */
class Source
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var boolean
     *
     * @ORM\Column(name="favorite", type="boolean")
     */
    private $favorite;

    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=255)
     */
    private $icon;

    /**
     * One Source has Many items.
     * @ORM\OneToMany(targetEntity="CoreBundle\Entity\Item", mappedBy="source")
     */
    private $items;

    /**
     * @ORM\ManyToMany(targetEntity="CoreBundle\Entity\Tag", inversedBy="sources")
     * @ORM\JoinTable(name="sources_tags")
     */
    private $tags;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetimetz", nullable=true)
     */
    private $updatedAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="error_count", type="integer", options={"default":0})
     */
    private $errorCount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_on", type="datetimetz", nullable=true)
     */
    private $updateOn;

    /**
     * Interval for updating Source in minutes
     *
     * @var integer
     *
     * @ORM\Column(name="update_interval", type="integer", options={"default":5})
     */
    private $updateInterval;

    /**
     * Source constructor.
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Source
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Source
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set icon
     *
     * @param string $icon
     *
     * @return Source
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Source
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Source
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Add item
     *
     * @param \CoreBundle\Entity\Item $item
     *
     * @return Source
     */
    public function addItem(\CoreBundle\Entity\Item $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param \CoreBundle\Entity\Item $item
     */
    public function removeItem(\CoreBundle\Entity\Item $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Add tag
     *
     * @param \CoreBundle\Entity\Tag $tag
     *
     * @return Source
     */
    public function addTag(\CoreBundle\Entity\Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \CoreBundle\Entity\Tag $tag
     */
    public function removeTag(\CoreBundle\Entity\Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set favorite
     *
     * @param boolean $favorite
     *
     * @return Source
     */
    public function setFavorite($favorite)
    {
        $this->favorite = $favorite;

        return $this;
    }

    /**
     * Get favorite
     *
     * @return boolean
     */
    public function getFavorite()
    {
        return $this->favorite;
    }

    /**
     * Set errorCount
     *
     * @param integer $errorCount
     *
     * @return Source
     */
    public function setErrorCount($errorCount)
    {
        $this->errorCount = $errorCount;

        return $this;
    }

    /**
     * Increment error counter
     *
     * @return Source
     */
    public function upErrorCount()
    {
        ++$this->errorCount;

        return $this;
    }

    /**
     * Get errorCount
     *
     * @return integer
     */
    public function getErrorCount()
    {
        return $this->errorCount;
    }

    /**
     * Set updateOn
     *
     * @param \DateTime $updateOn
     *
     * @return Source
     */
    public function setUpdateOn($updateOn)
    {
        $this->updateOn = $updateOn;

        return $this;
    }

    /**
     * Get updateOn
     *
     * @return \DateTime
     */
    public function getUpdateOn()
    {
        return $this->updateOn;
    }

    /**
     * Set updateInterval
     *
     * @param integer $updateInterval
     *
     * @return Source
     */
    public function setUpdateInterval($updateInterval)
    {
        $this->updateInterval = $updateInterval;

        return $this;
    }

    /**
     * Get updateInterval
     *
     * @return integer
     */
    public function getUpdateInterval()
    {
        return $this->updateInterval;
    }
}
