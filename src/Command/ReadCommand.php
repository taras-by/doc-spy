<?php

namespace App\Command;

use App\Entity\Item;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReadCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('read')
            ->setDescription('Read last items');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $items = $this->entityManager->getRepository(Item::class)->findLast();

        foreach ($items as $item) {
            /** @var Item $item */
            $output->writeln("\n" . $item->getPublishedAt()->format('d.m.Y'));
            $output->writeln($item->getTitle());
            $output->writeln('<info>' . trim(strip_tags($item->getDescription())) . '</info>');
            $output->writeln($item->getLink());
            $output->writeln('----------');
        }
    }
}
