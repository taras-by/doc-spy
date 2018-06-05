<?php

namespace App\Service\Parser;

use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Source;

class BaseParser
{
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
    protected $count = 0;

    /**
     * @var bool
     */
    protected $hasErrors = false;

    public function setSource(Source $source): ParserInterface
    {
        $this->items = new ArrayCollection();
        $this->count = 0;
        $this->source = $source;

        return $this;
    }

    public function getSource(): Source
    {
        return $this->source;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function hasErrors(): bool
    {
        return $this->hasErrors;
    }
}