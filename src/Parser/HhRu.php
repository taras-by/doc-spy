<?php

namespace App\Parser;

use App\Entity\Item;

class HhRu extends AbstractParser implements ParserInterface
{
    /**
     * @throws \Exception
     */
    protected function parse(): void
    {
        $document = $this->getDomDocument($this->source->getUrl());

        $finder = new \DomXPath($document);
        $itemNodes = $finder->query('//a[contains(@class,"cms-announce-tile")]');

        foreach ($itemNodes as $itemNode) {
            $titleNode = $finder->query('span[@class="bloko-link"]', $itemNode)[0];
            $title = $titleNode->nodeValue ?? null;

            $link = $itemNode->getAttribute('href');
            $link = $this->url($link);

            $item = (new Item())
                ->setTitle($title)
                ->setlink($link)
                ->setUid($link)
                ->setPublishedAt(new \DateTime())
                ->setSource($this->source);
            $this->items->add($item);

            ++$this->count;
        }
    }
}