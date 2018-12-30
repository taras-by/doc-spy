<?php

namespace App\Command;

use App\Entity\Item;
use App\Entity\Source;
use App\Event\SourceItemsAddedEvent;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class NotificationCheckItemsAddedCommand extends ContainerAwareCommand
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var RegistryInterface
     */
    private $entityManager;

    public function __construct(EventDispatcherInterface $dispatcher, RegistryInterface $entityManager)
    {
        $this->dispatcher = $dispatcher;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('notify:check:items-added')
            ->setDescription('Check event after Items added. Send last 5 Items of source to subscribers');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $source = $this->entityManager->getRepository(Source::class)
            ->createQueryBuilder('s')
            ->orderBy('s.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        $items = $this->entityManager->getRepository(Item::class)
            ->createQueryBuilder('i')
            ->where('i.source = :source')
            ->setParameter('source', $source)
            ->orderBy('i.publishedAt', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();

        $event = (new SourceItemsAddedEvent($source))
            ->setItems($items);

        $this->dispatcher->dispatch(SourceItemsAddedEvent::NAME, $event);
        $output->writeln('Finished!');
    }
}
