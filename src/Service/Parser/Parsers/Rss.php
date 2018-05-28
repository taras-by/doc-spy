<?php

namespace App\Service\Parser\Parsers;

use App\Entity\Item;
use App\Service\Parser\ParserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use FeedIo\FeedIo;
use FeedIo\Reader\ReadErrorException;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Service\Parser\BaseParser;

class Rss extends BaseParser implements ParserInterface
{
    /**
     * @var FeedIo
     */
    private $feedio;

    public function __construct(FeedIo $feedio, RegistryInterface $entityManager)
    {
        $this->feedio = $feedio;
        $this->entityManager = $entityManager;
    }

    public function getItems(): ArrayCollection
    {
        try {
            $feed = $this->feedio->read($this->source->getUrl())->getFeed();
            $this->allCount = count($feed);

            foreach ($feed as $feedItem) {
                if (!$this->getItemRepository()->findBy(['link' => $feedItem->getLink()])) {
                    $item = (new Item())
                        ->setTitle($feedItem->getTitle())
                        ->setDescription($feedItem->getDescription())
                        ->setlink($feedItem->getlink())
                        ->setPublishedAt($feedItem->getLastModified())
                        ->setSource($this->source);
                    $this->items->add($item);
                    ++$this->needAddCount;
                }
            }

        } catch (ReadErrorException $exception) {
            /**
             * @todo Logging
             */
            $this->hasErrors = true;
        }

        return $this->items;
    }
}