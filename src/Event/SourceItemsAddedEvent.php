<?php

namespace App\Event;

use App\Entity\Item;
use App\Entity\Source;
use Symfony\Contracts\EventDispatcher\Event;

class SourceItemsAddedEvent extends Event
{
    const NAME = 'source.items.added';

    /**
     * $var Source
     */
    protected $source;

    /**
     * @var Item[]
     */
    protected $items;

    public function __construct(Source $source)
    {
        $this->source = $source;
    }

    public function getSource(): Source
    {
        return $this->source;
    }

    public function setItems(array $items): self
    {
        $this->items = $items;

        return $this;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}