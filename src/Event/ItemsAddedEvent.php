<?php

namespace App\Event;

use App\Entity\Item;
use Symfony\Component\EventDispatcher\Event;

class ItemsAddedEvent extends Event
{
    const NAME = 'items.added';

    /**
     * @var Item[]
     */
    protected $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}