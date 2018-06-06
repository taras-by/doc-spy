<?php

namespace App\Command;

use App\Entity\Item;
use App\Entity\Source;
use App\Repository\SourceRepository;
use App\Service\Parser\ParserManager;
use App\Service\Parser\ParserInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ParserCheckCommand extends ContainerAwareCommand
{
    /**
     * @var ParserManager
     */
    private $parserManager;

    /**
     * @var RegistryInterface
     */
    private $entityManager;

    public function __construct(ParserManager $parserManager, RegistryInterface $entityManager)
    {
        $this->parserManager = $parserManager;
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('parser:check')
            ->setDescription('Check parser for source')
            ->addArgument('source_id', InputArgument::REQUIRED, 'Id of Source');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sourceId = $input->getArgument('source_id');

        $source = $this->getSourceRepository()->find($sourceId);
        $parser = $this->parserManager->getParser($source);
        $items = $parser->getItems();

        /** @var Item $item */
        foreach ($items as $item) {
            $this->writeItem($item, $output);
        }
        $this->writeSummary($parser, $output);
    }

    private function writeItem(Item $item, OutputInterface $output): void
    {
        $formatter = $this->getHelper('formatter');
        $output->writeln('Title: ' . $item->getTitle());
        $output->writeln('Description: ' . $formatter->truncate($item->getDescription(), 50));
        $output->writeln('Link: ' . $item->getLink());
        $output->writeln('Start date: ' . ($item->getStartDate() ? $item->getStartDate()->format(DATE_ATOM) : 'null'));
        $output->writeln('End date: ' . ($item->getEndDate() ? $item->getEndDate()->format(DATE_ATOM) : 'null'));
        $output->writeln('Published at: ' . $item->getPublishedAt()->format(DATE_ATOM));
        $output->writeln('-----');
    }

    private function writeSummary(ParserInterface $parser, OutputInterface $output): void
    {
        $output->writeln('Has errors: ' . ($parser->hasErrors() ? 'yes' : 'no'));
        $output->writeln('All count: ' . $parser->getCount());
    }

    private function getSourceRepository(): SourceRepository
    {
        return $this->entityManager->getRepository(Source::class);
    }
}
