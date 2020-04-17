<?php

namespace App\Parser;

use App\Reader\ReaderInterface;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Source;
use Throwable;

abstract class AbstractParser implements ParserInterface
{

    private const ERROR_ITEMS_NOT_FOUND = 'Items not found';

    /**
     * @var Source
     */
    protected $source;

    /**
     * @var ReaderInterface
     */
    protected $reader;

    /**
     * @var ArrayCollection
     */
    protected $items;

    /**
     * @var int
     */
    protected $count = 0;

    /**
     * @var string
     */
    protected $errorMessage = '';

    public function run()
    {
        $this->items = new ArrayCollection();
        $this->count = 0;
        $this->errorMessage = '';

        try {
            $this->parse();
            if ($this->count == 0) {
                $this->errorMessage = self::ERROR_ITEMS_NOT_FOUND;
            }
        } catch (Throwable $exception) {
            $this->items = new ArrayCollection();
            $this->errorMessage = $exception->getMessage() . PHP_EOL . $exception->getTraceAsString();
        }

        return $this;
    }

    abstract protected function parse(): void;

    public function setSource(Source $source): ParserInterface
    {
        $this->source = $source;

        return $this;
    }

    public function getSource(): Source
    {
        return $this->source;
    }

    public function setReader(ReaderInterface $reader): ParserInterface
    {
        $this->reader = $reader;

        return $this;
    }

    public function getReader(): ?ReaderInterface
    {
        return $this->reader;
    }

    public function getItems(): ArrayCollection
    {
        return $this->items;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function hasErrors(): bool
    {
        return $this->errorMessage ? true : false;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    protected function getDomDocument(string $path): \DOMDocument
    {
        $content = $this->getReader()->getContent($path);
        return $this->getDomDocumentFromContent($content);
    }

    private function getDomDocumentFromContent(string $content): \DOMDocument
    {
        $document = new \DOMDocument();

        libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="utf-8" ?>' . $content);
        libxml_use_internal_errors(false);

        return $document;
    }

    protected function url(string $url): string
    {
        $parsed_url = parse_url($this->source->getUrl());
        return $parsed_url['scheme'] . '://' . $parsed_url['host'] . $url;
    }

    protected function clearUrl(string $url): string
    {
        $parsed_url = parse_url($url);
        return $parsed_url['scheme'] . '://' . $parsed_url['host'] . $parsed_url['path'];
    }

    /**
     * @todo Need to make helper or service
     */
    protected function convertMonth(string $month): string
    {
        $months = [
            'янв' => 'january',
            'фев' => 'february',
            'мар' => 'march',
            'апр' => 'april',
            'май' => 'may',
            'июн' => 'june',
            'июл' => 'july',
            'авг' => 'august',
            'сен' => 'september',
            'окт' => 'october',
            'ноя' => 'november',
            'дек' => 'december',
        ];

        return $months[mb_strtolower($month)] ?? $month;
    }
}
