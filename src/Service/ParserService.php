<?php

namespace App\Service;

use App\Entity\Item;
use App\Repository\ItemRepository;
use FeedIo\FeedIo;
use FeedIo\Reader\ReadErrorException;
use App\Entity\Source;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @todo add two methods for reading Source and for saving Items
 */
class ParserService
{

    /**
     * @var integer
     */
    private $allCount = 0;

    /**
     * @var integer
     */
    private $addedCount = 0;

    /**
     * Current time with rounding to minutes
     *
     * @var \DateTime
     */
    private $now;

    /**
     * @var FeedIo
     */
    private $feedio;

    /**
     * @var RegistryInterface
     */
    private $entityManager;

    /**
     * ParserService constructor
     *
     * @param FeedIo $feedio
     * @param RegistryInterface $entityManager
     */
    public function __construct(FeedIo $feedio, RegistryInterface $entityManager)
    {
        $this->feedio = $feedio;
        $this->entityManager = $entityManager;
        $this->now = new \DateTime(date('H:i'));
    }

    /**
     * Run parser
     *
     * @param Source $source
     * @throws \Exception
     */
    public function read(Source $source)
    {
        $this->addedCount = 0;
        $this->allCount = 0;

        /** @var ItemRepository $itemRepository */
        $itemRepository = $this->entityManager->getRepository(Item::class);

        $em = $this->entityManager->getManager();

        try {
            $feed = $this->feedio->read($source->getUrl())->getFeed();
            $this->allCount = count($feed);
            foreach ($feed as $feedItem) {
                if (!$itemRepository->findByLink($feedItem->getLink())) {

                    $item = new Item();
                    $item->setTitle($feedItem->getTitle());
                    $item->setDescription($feedItem->getDescription());
                    $item->setlink($feedItem->getlink());
                    $item->setPublishedAt($feedItem->getLastModified());
                    $item->setSource($source);
                    $em->persist($item);
                    $this->addedCount++;
                }
            }
            $source->setErrorCount(0);
            $source->setUpdatedAt(new \DateTime());
        } catch (ReadErrorException $exception) {
            /**
             * @todo Logging
             */
            $source->upErrorCount();
        }

        $nextUpdateTime = $this->getNextUpdateTime($source->getUpdateInterval(), $source->getErrorCount());
        $source->setUpdateOn($nextUpdateTime);

        $em->flush();
    }

    /**
     * Get count of all parsed items
     *
     * @return integer
     */
    public function getAllCount()
    {
        return $this->allCount;
    }

    /**
     * Get count of new added items
     *
     * @return integer
     */
    public function getAddedCount()
    {
        return $this->addedCount;
    }

    /**
     * Set next update time
     *
     * @param integer $updateInteval
     * @param integer $errorCount
     * @return \DateTime
     * @throws \Exception
     */
    private function getNextUpdateTime($updateInteval, $errorCount)
    {
        $now = clone $this->now;
        return $now->add(new \DateInterval('PT' . $updateInteval * ($errorCount + 1) . 'M'));
    }
}