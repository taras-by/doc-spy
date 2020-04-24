<?php

namespace App\Entity;

use App\Traits\CreatorTrait;
use App\Traits\EnableStatusTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Source
 *
 * @ORM\Table(name="source")
 * @ORM\Entity(repositoryClass="App\Repository\SourceRepository")
 */
class Source
{
    use CreatorTrait;
    use EnableStatusTrait;

    const VISIBILITY_MAIN = 'main';
    const VISIBILITY_PUBLIC = 'public';
    const VISIBILITY_PROTECTED = 'protected';
    const VISIBILITY_PRIVATE = 'private';

    const VISIBILITY_LABELS = [
        self::VISIBILITY_MAIN => 'Main',
        self::VISIBILITY_PUBLIC => 'Public',
        self::VISIBILITY_PROTECTED => 'Protected',
        self::VISIBILITY_PRIVATE => 'Private',
    ];

    const VISIBILITY_LABEL_CLASSES = [
        self::VISIBILITY_MAIN => 'info',
        self::VISIBILITY_PUBLIC => 'info',
        self::VISIBILITY_PROTECTED => 'secondary',
        self::VISIBILITY_PRIVATE => 'secondary',
    ];

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
     * @Assert\Length( min = 4, max = 255, )
     * @Assert\NotNull()
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @Assert\Url()
     * @Assert\Length( min = 12, max = 2048, )
     * @Assert\NotNull()
     * @ORM\Column(name="url", type="string", length=2048)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="parser", type="string", length=255)
     */
    private $parser;

    /**
     * @var string
     *
     * @Assert\Url()
     * @ORM\Column(name="icon", type="string", length=255)
     */
    private $icon;

    /**
     * @var string
     *
     * @ORM\Column(name="visibility", type="string", length=255)
     */
    private $visibility;

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
     * @Assert\Positive
     * @ORM\Column(name="update_interval", type="integer", options={"default":60})
     */
    private $updateInterval = 60;

    /**
     * Items TTL in days
     *
     * @var integer
     *
     * @Assert\Positive
     * @ORM\Column(name="items_days_to_live", type="integer", options={"default":90})
     */
    private $itemsDaysToLive = 90;

    /**
     * @ORM\OneToMany(targetEntity="Subscription", mappedBy="source")
     */
    private $subscriptions;

    /**
     * Source constructor.
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
        $this->visibility = self::VISIBILITY_PUBLIC;
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
     * @return string
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * @param string $visibility
     *
     * @return Source
     */
    public function setVisibility(string $visibility)
    {
        $this->visibility = $visibility;

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
     * @return Collection
     */
    public function getItems(): Collection
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
     * @return Collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
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
     * @return int
     */
    public function getItemsDaysToLive(): int
    {
        return $this->itemsDaysToLive;
    }

    /**
     * @param int $itemsDaysToLive
     * @return Source
     */
    public function setItemsDaysToLive(int $itemsDaysToLive)
    {
        $this->itemsDaysToLive = $itemsDaysToLive;

        return $this;
    }

    /**
     * @return Collection|Subscription[]
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscription(Subscription $subscription): self
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions[] = $subscription;
            $subscription->setSource($this);
        }

        return $this;
    }

    public function removeSubscription(Subscription $subscription): self
    {
        if ($this->subscriptions->contains($subscription)) {
            $this->subscriptions->removeElement($subscription);
            // set the owning side to null (unless already changed)
            if ($subscription->getSource() === $this) {
                $subscription->setSource(null);
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isVisibleToEveryone()
    {
        return in_array($this->getVisibility(), [
            self::VISIBILITY_MAIN,
            self::VISIBILITY_PUBLIC,
        ]);
    }

    /**
     * @return bool
     */
    public function isPrivate()
    {
        return $this->getVisibility() == self::VISIBILITY_PRIVATE;
    }

    /**
     * @return string
     */
    public function getVisibilityLabel(): string
    {
        return self::VISIBILITY_LABELS[$this->visibility];
    }

    /**
     * @return string
     */
    public function getVisibilityLabelClass(): string
    {
        return self::VISIBILITY_LABEL_CLASSES[$this->visibility];
    }

    public static function getVisibilityChoices(): array
    {
        return array_flip(self::VISIBILITY_LABELS);
    }
}
