<?php

namespace App\Command;

use App\Entity\Source;
use App\Event\SourceParsingErrorEvent;
use App\Repository\SourceRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class NotificationCheckSourceParsingErrorCommand extends Command
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
     * @param InputInterface $input
     * @param OutputInterface $output
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
        $source->setErrorCount(5);

        $event = (new SourceParsingErrorEvent($source))->setMessage("Message string 1\nMessage string 2");

        $this->dispatcher->dispatch(SourceParsingErrorEvent::NAME, $event);
        $output->writeln('Finished!');
    }
}
