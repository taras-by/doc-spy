<?php

namespace App\Parser;

use App\Entity\Item;
use FeedIo\FeedIo;

class Rss extends AbstractParser implements ParserInterface
{
    /**
     * @var FeedIo
     */
    private $feedio;

    public function __construct(FeedIo $feedio)
    {
        $this->feedio = $feedio;
    }

    /**
     * @throws \Exception
     */
    protected function parse(): void
    {
        $feed = $this->feedio->read($this->source->getUrl())->getFeed();
        $this->count = count($feed);

        foreach ($feed as $feedItem) {
            $item = (new Item())
                ->setTitle($feedItem->getTitle())
//                    ->setDescription($feedItem->getDescription())
                ->setlink($feedItem->getlink())
                ->setUid($feedItem->getlink())
                ->setPublishedAt($feedItem->getLastModified())
                ->setSource($this->source);
            $this->items->add($item);
        }
    }
}