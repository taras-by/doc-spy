<?php

namespace App\Command;

use App\Entity\Item;
use App\Repository\ItemRepository;
use App\Traits\EntityManagerTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CleanCommand extends Command
{
    const DAYS_TO_LIVE = 90;
    
    protected static $defaultName = 'clean';
    protected static $description = 'Command to clean the database';

    /**
     * @var ItemRepository
     */
    private $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
        parent::__construct();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $result = $this->itemRepository
            ->deleteOlderThenDate(new \DateTime(sprintf('now -%s days', self::DAYS_TO_LIVE)));
        $io->success(sprintf('%s old Items were deleted', $result));
    }
}