<?php

namespace App\Command;

use App\Entity\Item;
use App\Event\ItemsAddedEvent;
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
            ->setDescription('Check event after Items added. Send last 5 Items to subscribers');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $items = $this->entityManager->getRepository(Item::class)
            ->createQueryBuilder('i')
            ->orderBy('i.publishedAt', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();

        $event = new ItemsAddedEvent($items);

        $this->dispatcher->dispatch(ItemsAddedEvent::NAME, $event);
        $output->writeln('Finished!');
    }
}
