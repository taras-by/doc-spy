<?php

namespace App\Parser;

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
     * @var string
     */
    protected $errorMessage = '';

    public function setSource(Source $source): ParserInterface
    {
        $this->items = new ArrayCollection();
        $this->count = 0;
        $this->errorMessage = '';
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
        return $this->errorMessage ? true : false;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    public function setErrorMessage(string $errorMessage): ParserInterface
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    protected function getDomDocument(string $path): \DOMDocument
    {
        $content = file_get_contents($path);
        return $this->getDomDocumentFromContent($content);
    }

    protected function getDomDocumentFromContent(string $content): \DOMDocument
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
