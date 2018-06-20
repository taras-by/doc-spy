<?php

namespace App\Service\Parser\Parsers;

use App\Entity\Item;
use App\Service\Parser\ParserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use App\Service\Parser\BaseParser;

class HhRu extends BaseParser implements ParserInterface
{
    /**
     * @return ArrayCollection
     * @throws \Exception
     */
    public function getItems(): ArrayCollection
    {
        try {
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
                    ->setPublishedAt(new \DateTime())
                    ->setSource($this->source);
                $this->items->add($item);

                ++$this->count;
            }

        } catch (\Exception $exception) {
            $this->hasErrors = true;
//            throw $exception;
        }

        return $this->items;
    }

    protected function getDomDocument($path)
    {
        $content = file_get_contents($path);
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
}