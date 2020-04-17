<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SubscriptionRepository")
 */
class Subscription
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Source", inversedBy="subscriptions")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $source;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="subscriptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var \DateTime    
     *  
     * @ORM\Column(name="expire_at", type="datetimetz", nullable=true)
     */
    private $expireAt;

    /**
     * @ORM\Column(name="is_notify", type="boolean")
     */
    private $isNotify;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSource(): ?Source
    {
        return $this->source;
    }

    public function setSource(?Source $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getExpireAt(): ?\DateTime
    {
        return $this->expireAt;
    }

    public function serExpireAt(\DateTime $expireAt): self
    {
        $this->expireAt = $expireAt;

        return $this;
    }

    public function getIsNotify()
    {
        return $this->isNotify;
    }

    public function setIsNotify($isNotify): self
    {
        $this->isNotify = $isNotify;

        return $this;
    }
}
