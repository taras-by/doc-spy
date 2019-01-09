<?php

namespace App\Traits;

use App\Entity\User;

trait CreatorTrait
{
    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="created_by", nullable=true)
     */
    private $createdBy;

    public function setCreatedBy(User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }
}
