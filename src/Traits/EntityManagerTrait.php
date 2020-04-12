<?php

namespace App\Traits;

use Doctrine\ORM\EntityManagerInterface;

trait EntityManagerTrait
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    protected function getEntityManager(): ?EntityManagerInterface
    {
        return $this->entityManager;
    }
}
