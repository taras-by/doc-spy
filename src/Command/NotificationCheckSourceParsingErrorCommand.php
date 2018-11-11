<?php

namespace App\Command;

use App\Entity\Item;
use App\Entity\Source;
use App\Event\ItemsAddedEvent;
use App\Event\SourceParsingErrorEvent;
use App\Repository\SourceRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class NotificationCheckSourceParsingErrorCommand extends ContainerAwareCommand
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
            ->setName('notify:check:source-parsing-error')
            ->setDescription('Check event after Source parsing error. Generate source with error');
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var SourceRepository $repository */
        $repository = $this->entityManager->getRepository(Source::class);
        $source = $repository
            ->createQueryBuilder('s')
            ->orderBy('s.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        $event = (new SourceParsingErrorEvent($source))->setMessage("Message string 1\nMessage string 2");

        $this->dispatcher->dispatch(SourceParsingErrorEvent::NAME, $event);
        $output->writeln('Finished!');
    }
}
