<?php

namespace App\Command;

use App\Entity\Source;
use App\Repository\SourceRepository;
use App\Service\ParserHandler;
use App\Service\ParserManager;
use DateTime;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ParserRunCommand extends Command
{
    /**
     * @var ParserManager
     */
    private $parserManager;

    /**
     * @var ParserHandler
     */
    private $parserHandler;

    /**
     * Current time with rounding to minutes
     *
     * @var DateTime
     */
    private $now;

    /**
     * @var SourceRepository
     */
    private $sourceRepository;

    /**
     * @param ParserManager $parserManager
     * @param ParserHandler $parserHandler
     * @param SourceRepository $sourceRepository
     * @throws Exception
     */
    public function __construct(
        ParserManager $parserManager,
        ParserHandler $parserHandler,
        SourceRepository $sourceRepository
    )
    {
        $this->parserManager = $parserManager;
        $this->parserHandler = $parserHandler;
        $this->now = new DateTime(date('H:i'));
        $this->sourceRepository = $sourceRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('parser:run')
            ->setDescription('Parse document')
            ->addOption('limit', 'l', InputOption::VALUE_OPTIONAL, 'Count of Sources for parsing')
            ->addOption('id', 'i', InputOption::VALUE_OPTIONAL, 'Source ID')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force group update');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('[start] ' . (new DateTime())->format('Y-m-d H:i:s'));
        $limit = $input->getOption('limit');
        $id = $input->getOption('id');
        $force = $input->getOption('force');

        $parserHandler = $this->parserHandler;
        $sourceRepository = $this->sourceRepository;

        if ($id) {
            $sources = $sourceRepository->findBy(['id' => $id]);
        } else {
            $sources = $sourceRepository->findForUpdate($limit, $force);
        }

        /** @var Source $source */
        foreach ($sources as $source) {
            $parser = $this->parserManager->getParser($source);
            $parser->run();
            $parserHandler->handle($parser);

            $writelnIfSaved = $parserHandler->getSavedCount() ?
                sprintf(' <info>new items: %s</info>', $parserHandler->getSavedCount()) : '';
            $output->writeln(sprintf('Parsed: %s', $source->getName()));
            $output->writeln(sprintf('  Received items: %s', $parserHandler->getAllCount()) . $writelnIfSaved);
            if ($parser->hasErrors()) {
                $output->writeln($parser->getErrorMessage());
            }
        }
        $output->writeln('[done] ' . (new DateTime())->format('Y-m-d H:i:s'));

        return 0;
    }
}
