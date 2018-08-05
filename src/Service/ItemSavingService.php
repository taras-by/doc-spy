<?php

namespace App\Service;

use App\Entity\Item;
use App\Repository\ItemRepository;
use App\Parser\ParserInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ItemSavingService
{
    /**
     * @var RegistryInterface
     */
    private $entityManager;

    /**
     * Current time with rounding to minutes
     *
     * @var \DateTime
     */
    private $now;

    /**
     * @var int
     */
    private $allCount = 0;

    /**
     * @var int
     */
    private $savedCount = 0;

    /**
     * ItemService constructor.
     * @param RegistryInterface $entityManager
     */
    public function __construct(RegistryInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->now = new \DateTime(date('H:i'));
    }

    /**
     * @param ParserInterface $parser
     * @throws \Exception
     */
    public function save(ParserInterface $parser): void
    {
        $this->savedCount = 0;
        $source = $parser->getSource();

        /** @var Item $item */
        foreach ($parser->getItems() as $item) {
            if (!$this->getItemRepository()->findBy(['link' => $item->getLink()])) {
                $this->entityManager->getManager()->persist($item);
                ++$this->savedCount;
            }
        }

        if ($parser->hasErrors()) {
            $source->upErrorCount();
        } else {
            $source->setErrorCount(0);
            $source->setUpdatedAt(new \DateTime());
        }

        $nextUpdateTime = $this->getNextUpdateTime($source->getUpdateInterval(), $source->getErrorCount());
        $source->setScheduleAt($nextUpdateTime);

        $this->entityManager->getManager()->flush();
        $this->allCount = $parser->getCount();
    }

    public function getAllCount(): int
    {
        return $this->allCount;
    }

    public function getSavedCount(): int
    {
        return $this->savedCount;
    }

    /**
     * @param $updateInteval
     * @param $errorCount
     * @return \DateTime
     * @throws \Exception
     */
    private function getNextUpdateTime($updateInteval, $errorCount): \DateTime
    {
        $now = clone $this->now;
        return $now->add(new \DateInterval('PT' . $updateInteval * ($errorCount + 1) . 'M'));
    }

    protected function getItemRepository(): ItemRepository
    {
        return $this->entityManager->getRepository(Item::class);
    }
}