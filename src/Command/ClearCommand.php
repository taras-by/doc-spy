<?php

namespace App\Command;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ClearCommand extends Command
{
    const DAYS_TO_LIVE = 90;

    protected static $defaultName = 'clean';

    /**
     * @var RegistryInterface
     */
    private $entityManager;

    public function __construct(RegistryInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Command to clean the database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $result = $this->getItemRepository()
            ->deleteOlderThenDate(new \DateTime(sprintf('now -%s days', self::DAYS_TO_LIVE)));
        $io->success(sprintf('%s old Items were deleted', $result));
    }

    private function getItemRepository(): ItemRepository
    {
        return $this->entityManager->getRepository(Item::class);
    }
}
