<?php

namespace App\Command;

use App\Entity\Source;
use App\Repository\SourceRepository;
use App\Service\ItemSavingService;
use App\Service\Parser\ParserManager;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParserRunCommand extends ContainerAwareCommand
{
    /**
     * @var ParserManager
     */
    private $parserManager;

    /**
     * @var ItemSavingService
     */
    private $itemSavingService;

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

    public function __construct(ParserManager $parserManager, ItemSavingService $itemSavingService, RegistryInterface $entityManager)
    {
        $this->parserManager = $parserManager;
        $this->itemSavingService = $itemSavingService;
        $this->entityManager = $entityManager;
        $this->now = new \DateTime(date('H:i'));

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('parser:run')
            ->setDescription('Parse document')
            ->addArgument('results', InputArgument::OPTIONAL, 'Count of Sources for parsing');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $results = $input->getArgument('results');
        $sources = $this->getSourceRepository()->findForUpdate($results);
        $saver = $this->itemSavingService;

        /** @var Source $source */
        foreach ($sources as $source) {
            $parser = $this->parserManager->getParser($source);
            $saver->save($parser);

            $writelnIfSaved = $saver->getSavedCount() ?
                sprintf(' <info>new items: %s</info>', $saver->getSavedCount()) : '';
            $output->writeln(sprintf('Parsed: %s', $source->getName()));
            $output->writeln(sprintf('  Received items: %s', $saver->getAllCount()) . $writelnIfSaved);
        }
    }

    private function getSourceRepository(): SourceRepository
    {
        return $this->getContainer()->get('doctrine')->getRepository(Source::class);
    }
}
