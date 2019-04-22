<?php

namespace App\Parser;

use App\Entity\Item;

class RelaxBy extends AbstractParser implements ParserInterface
{
    /**
     * @throws \Exception
     */
    protected function parse(): void
    {
        $document = $this->getDomDocument($this->source->getUrl());

        $finder = new \DomXPath($document);
        $itemNodes = $finder->query('//a[contains(@class,"b-journal_rubrika-new_item_title")]');

        foreach ($itemNodes as $itemNode) {
            $title = $itemNode->nodeValue ?? null;

            $link = $itemNode->getAttribute('href');
            $link = $this->url($link);

            $item = (new Item())
                ->setTitle($title)
                ->setlink($link)
                ->setPublishedAt(new \DateTime())
                ->setSource($this->source);
            $this->items->add($item);

            ++$this->count;
        }
    }
}