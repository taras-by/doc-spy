<?php

namespace ParserBundle\Service;

use CoreBundle\Entity\Item;
use CoreBundle\Repository\ItemRepository;
use FeedIo\FeedIo;
use ParserBundle\Entity\Source;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @todo add two methods for reading Sourse and for saving Items
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
     * @var FeedIo
     */
    private $feedio;

    /**
     * @var RegistryInterface
     */
    private $doctrine;

    /**
     * ParserService constructor
     *
     * @param FeedIo $feedio
     * @param RegistryInterface $doctrine
     */
    public function __construct(FeedIo $feedio, RegistryInterface $doctrine)
    {
        $this->feedio = $feedio;
        $this->doctrine = $doctrine;
    }

    /**
     * Run parser
     *
     * @param Source $source
     */
    public function read(Source $source)
    {
        /** @var ItemRepository $itemRepository */
        $itemRepository = $this->doctrine->getRepository(Item::class);

        $feed = $this->feedio->read($source->getUrl())->getFeed();
        $this->allCount = count($feed);

        $em = $this->doctrine->getManager();

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
        $source->setUpdatedAt(new \DateTime());
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
}