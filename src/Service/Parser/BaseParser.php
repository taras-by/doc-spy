<?php

namespace App\Service\Parser;

use App\Repository\ItemRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Item;
use App\Entity\Source;

class BaseParser
{
    /**
     * @var RegistryInterface
     */
    protected $entityManager;

    /**
     * @var Source
     */
    protected $source;

    /**
     * @var ArrayCollection
     */
    protected $items;

    /**
     * @var int
     */
    protected $needAddCount = 0;

    /**
     * @var int
     */
    protected $allCount = 0;

    /**
     * @var bool
     */
    protected $hasErrors = false;

    public function __construct(RegistryInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function setSource(Source $source): ParserInterface
    {
        $this->items = new ArrayCollection();
        $this->needAddCount = 0;
        $this->source = $source;

        return $this;
    }

    public function getAllCount(): int
    {
        return $this->allCount;
    }

    public function getNeedAddCount(): int
    {
        return $this->needAddCount;
    }

    public function hasErrors(): bool
    {
        return $this->hasErrors;
    }

    protected function getItemRepository(): ItemRepository
    {
        return $this->entityManager->getRepository(Item::class);
    }
}