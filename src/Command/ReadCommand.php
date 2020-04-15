<?php

namespace App\Command;

use App\Repository\ItemRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ReadCommand extends Command
{
    /**
     * @var ItemRepository
     */
    private $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('read')
            ->setDescription('Read last items')
            ->addOption('results', 'r', InputOption::VALUE_OPTIONAL, 'Number of items displayed', 20);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $results = $input->getOption('results');
        $items = $this->itemRepository->findLast($results);

        foreach ($items as $item) {
            $output->writeln("\n" . $item->getPublishedAt()->format('d.m.Y'));
            $output->writeln($item->getTitle());
            $output->writeln('<info>' . trim(strip_tags($item->getDescription())) . '</info>');
            $output->writeln($item->getLink());
            $output->writeln('----------');
        }

        return 0;
    }
}
