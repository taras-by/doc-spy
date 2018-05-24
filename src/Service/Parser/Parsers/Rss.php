<?php

namespace App\Service\Parser\Parsers;

use App\Entity\Item;
use App\Entity\Source;
use App\Repository\ItemRepository;
use App\Service\Parser\ParserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use FeedIo\FeedIo;
use FeedIo\Reader\ReadErrorException;
use Symfony\Bridge\Doctrine\RegistryInterface;

class Rss implements ParserInterface
{
    /**
     * @var FeedIo
     */
    private $feedio;

    /**
     * @var RegistryInterface
     */
    private $entityManager;

    /**
     * @var int
     */
    private $needAddCount = 0;

    /**
     * @var int
     */
    private $allCount = 0;

    /**
     * @var bool
     */
    private $hasErrors = false;

    public function __construct(FeedIo $feedio, RegistryInterface $entityManager)
    {
        $this->feedio = $feedio;
        $this->entityManager = $entityManager;
    }

    public function getItems(Source $source): ArrayCollection
    {
        $items = new ArrayCollection();
        $this->needAddCount = 0;

        try {
            $feed = $this->feedio->read($source->getUrl())->getFeed();
            $this->allCount = count($feed);

            foreach ($feed as $feedItem) {
                if (!$this->getItemRepository()->findBy(['link' => $feedItem->getLink()])) {

                    $item = new Item();
                    $item->setTitle($feedItem->getTitle());
                    $item->setDescription($feedItem->getDescription());
                    $item->setlink($feedItem->getlink());
                    $item->setPublishedAt($feedItem->getLastModified());
                    $item->setSource($source);
                    $items->add($item);
                    ++$this->needAddCount;
                }
            }

        } catch (ReadErrorException $exception) {
            /**
             * @todo Logging
             */
            $this->hasErrors = true;
        }

        return $items;
    }

    public function getAllCount(): int
    {
        return $this->allCount;
    }

    public function getNeedAddCount(): int
    {
        return $this->needAddCount;
    }

    public function hasErrors(): bool
    {
        return $this->hasErrors;
    }

    private function getItemRepository(): ItemRepository
    {
        return $this->entityManager->getRepository(Item::class);
    }
}