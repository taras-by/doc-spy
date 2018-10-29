<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Source
 *
 * @ORM\Table(name="source")
 * @ORM\Entity(repositoryClass="App\Repository\SourceRepository")
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
     * @var string
     *
     * @ORM\Column(name="parser", type="string", length=255, options={"default" : "rss"})
     */
    private $parser;

    /**
     * @var boolean
     *
     * @ORM\Column(name="favorite", type="boolean")
     */
    private $favorite = false;

    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=255)
     */
    private $icon;

    /**
     * One Source has Many items.
     * @ORM\OneToMany(targetEntity="App\Entity\Item", mappedBy="source")
     */
    private $items;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="sources")
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
    private $errorCount = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="schedule_at", type="datetimetz", nullable=true)
     */
    private $scheduleAt;

    /**
     * Interval for updating Source in minutes
     *
     * @var integer
     *
     * @ORM\Column(name="update_interval", type="integer", options={"default":60})
     */
    private $updateInterval = 60;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Subscribe", mappedBy="source")
     */
    private $subscribes;

    /**
     * Source constructor.
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->subscribes = new ArrayCollection();
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
     * @param string $parser
     *
     * @return Source
     */
    public function setParser($parser)
    {
        $this->parser = $parser;

        return $this;
    }

    /**
     * @return string
     */
    public function getParser()
    {
        return $this->parser;
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
     * @param Item $item
     *
     * @return Source
     */
    public function addItem(Item $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param Item $item
     */
    public function removeItem(Item $item)
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
     * @param Tag $tag
     *
     * @return Source
     */
    public function addTag(Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param Tag $tag
     */
    public function removeTag(Tag $tag)
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
     * @param \DateTime $scheduleAt
     *
     * @return Source
     */
    public function setScheduleAt($scheduleAt)
    {
        $this->scheduleAt = $scheduleAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getScheduleAt()
    {
        return $this->scheduleAt;
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

    /**
     * @return Collection|Subscribe[]
     */
    public function getSubscribes(): Collection
    {
        return $this->subscribes;
    }

    public function addSubscribe(Subscribe $subscribe): self
    {
        if (!$this->subscribes->contains($subscribe)) {
            $this->subscribes[] = $subscribe;
            $subscribe->setSource($this);
        }

        return $this;
    }

    public function removeSubscribe(Subscribe $subscribe): self
    {
        if ($this->subscribes->contains($subscribe)) {
            $this->subscribes->removeElement($subscribe);
            // set the owning side to null (unless already changed)
            if ($subscribe->getSource() === $this) {
                $subscribe->setSource(null);
            }
        }

        return $this;
    }
}
