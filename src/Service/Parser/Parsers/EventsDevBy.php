<?php

namespace App\Service\Parser\Parsers;

use App\Entity\Item;
use App\Entity\Source;
use App\Repository\ItemRepository;
use App\Service\Parser\ParserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Service\Parser\BaseParser;

class EventsDevBy extends BaseParser implements ParserInterface
{
    public function getItems(): ArrayCollection
    {
        try {

            $doc = $this->getDomDocument($this->source->getUrl());

            $finder = new \DomXPath($doc);
            $itemNodes = $finder->query("//*[contains(@class, 'list-item-events')]/div[@class='item']");
            
            foreach($itemNodes as $itemNode){
                $titleNode = $finder->query("div/a[@class='title']", $itemNode)[0];
                $title = $titleNode->nodeValue ?? null;
                $link = $titleNode->getAttribute('href');

                $descriptionNode = $finder->query("div/p", $itemNode)[0];
                $descriptionHtml = str_replace("\n", ' ', $descriptionNode->ownerDocument->saveHTML($descriptionNode));
                // Parse HTML: <p><time>text<time/>description</p>
                preg_match('/<\/time>(.+)<\/p>/', $descriptionHtml, $matches);
                $description = $matches[1] ?? null;
                
                // $idNode = $finder->query("div/div[@class='status-event']", $itemNode)[0];
                // $id = $idNode->getAttribute('id');

                // $dateNode = $finder->query("div/ul[@class='list-gray']/li/a", $itemNode)[1];
                // $googleCalendarHref = $dateNode->getAttribute('href');

                $item = (new Item())
                    ->setTitle($title)
                    ->setDescription($description)
                    ->setlink($link)
                    ->setPublishedAt(new \DateTime())
                    ->setSource($this->source);
                $this->items->add($item);
                ++$this->needAddCount;
            }

        } catch (\Exception $exception) {
            $this->hasErrors = true;
            throw $exception;
        }
        return $this->items;
    }

    protected function getDomDocument($path)
    {
        $content = file_get_contents($path);
        $doc = new \DOMDocument();

        libxml_use_internal_errors(true);
        $doc->loadHTML($content);
        libxml_use_internal_errors(false);

        return $doc;
    }
}