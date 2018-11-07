<?php

namespace App\Event;

use App\Entity\Source;
use Symfony\Component\EventDispatcher\Event;

class SourceParsingErrorEvent extends Event
{
    const NAME = 'source.parsing_error';

    /**
     * @var Source
     */
    protected $source;

    /**
     * @var string
     */
    protected $message;

    public function __construct(Source $source)
    {
        $this->source = $source;
    }

    public function getSource(): Source
    {
        return $this->source;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }
}