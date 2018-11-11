<?php

namespace App\Parser;

use App\Entity\Item;
use Doctrine\Common\Collections\ArrayCollection;
use FeedIo\FeedIo;
use FeedIo\Reader\ReadErrorException;

class Rss extends BaseParser implements ParserInterface
{
    /**
     * @var FeedIo
     */
    private $feedio;

    public function __construct(FeedIo $feedio)
    {
        $this->feedio = $feedio;
    }

    public function getItems(): ArrayCollection
    {
        try {
            $feed = $this->feedio->read($this->source->getUrl())->getFeed();
            $this->count = count($feed);

            foreach ($feed as $feedItem) {
                $item = (new Item())
                    ->setTitle($feedItem->getTitle())
//                    ->setDescription($feedItem->getDescription())
                    ->setlink($feedItem->getlink())
                    ->setPublishedAt($feedItem->getLastModified())
                    ->setSource($this->source);
                $this->items->add($item);
            }

        } catch (ReadErrorException $exception) {
            $this->hasErrors = true;
            $this->errorMessage = $exception->getMessage(). PHP_EOL .$exception->getTraceAsString();
        }

        return $this->items;
    }
}