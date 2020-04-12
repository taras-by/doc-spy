<?php

namespace App\Service;

use App\Entity\Item;
use App\Event\SourceItemsAddedEvent;
use App\Event\SourceParsingErrorEvent;
use App\Repository\ItemRepository;
use App\Parser\ParserInterface;
use App\Traits\EntityManagerTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ParserHandler
{
    use EntityManagerTrait;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

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
     * ParserHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param EventDispatcherInterface $dispatcher
     * @throws \Exception
     */
    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $dispatcher)
    {
        $this->entityManager = $entityManager;
        $this->dispatcher = $dispatcher;
        $this->now = new \DateTime(date('H:i'));
    }

    /**
     * @param ParserInterface $parser
     * @throws \Exception
     */
    public function handle(ParserInterface $parser): void
    {
        $this->savedCount = 0;
        $source = $parser->getSource();
        $persistedItems = [];

        /** @var Item $item */
        foreach ($parser->getItems() as $item) {
            if (!$this->getItemRepository()->findBy(['uid' => $item->getUid()])) {
                $this->getEntityManager()->persist($item);
                $persistedItems[] = $item;
                ++$this->savedCount;
            }
        }

        if ($parser->hasErrors()) {
            $source->upErrorCount();
            $event = (new SourceParsingErrorEvent($source))->setMessage($parser->getErrorMessage());
            $this->dispatcher->dispatch(SourceParsingErrorEvent::NAME, $event);
        } else {
            $source->setErrorCount(0);
            $source->setUpdatedAt(new \DateTime());
        }

        if (count($persistedItems) && !$parser->hasErrors()) {
            $event = (new SourceItemsAddedEvent($source))
                ->setItems($persistedItems);
            $this->dispatcher->dispatch(SourceItemsAddedEvent::NAME, $event);
        }

        $nextUpdateTime = $this->getNextUpdateTime($source->getUpdateInterval(), $source->getErrorCount());
        $source->setScheduleAt($nextUpdateTime);

        $this->getEntityManager()->flush();
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
     * @throws \Exception
     */
    private function getNextUpdateTime($updateInterval, $errorCount): \DateTime
    {
        $now = clone $this->now;
        return $now->add(new \DateInterval('PT' . $updateInterval * ($errorCount + 1) . 'M'));
    }

    protected function getItemRepository(): ItemRepository
    {
        return $this->entityManager->getRepository(Item::class);
    }
}