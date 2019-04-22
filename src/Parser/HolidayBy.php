<?php

namespace App\Parser;

use App\Entity\Item;

class HolidayBy extends AbstractParser implements ParserInterface
{
    /**
     * @throws \Exception
     */
    protected function parse(): void
    {
        $document = $this->getDomDocument($this->source->getUrl());

        $finder = new \DomXPath($document);
        $itemNodes = $finder->query('//div[contains(@class,"blog-item")]');

        foreach ($itemNodes as $itemNode) {
            $titleNode =
                $finder->query('div/a[contains(@class,"tile")]/div/div/div[contains(@class,"tile__title")]', $itemNode)[0] ??
                $finder->query('div/div/a[contains(@class,"column__link")]', $itemNode)[0];
            $title = trim($titleNode->nodeValue ?? null);

            $linkNode =
                $finder->query('div/a[contains(@class,"tile")]', $itemNode)[0] ??
                $finder->query('div/div/a[contains(@class,"column__link")]', $itemNode)[0];
            $link = $linkNode->getAttribute('href');
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