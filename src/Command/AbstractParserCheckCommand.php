<?php

namespace App\Command;

use App\Entity\Item;
use App\Service\ParserManager;
use App\Parser\ParserInterface;
use App\Traits\EntityManagerTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractParserCheckCommand extends Command
{
    use EntityManagerTrait;

    /**
     * @var \App\Service\ParserManager
     */
    protected $parserManager;

    public function __construct(ParserManager $parserManager, EntityManagerInterface $entityManager)
    {
        $this->parserManager = $parserManager;
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function writeItem(Item $item, OutputInterface $output): void
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

    protected function writeSummary(ParserInterface $parser, OutputInterface $output): void
    {
        $output->writeln('All count: ' . $parser->getCount());
        $output->writeln('Has errors: ' . ($parser->hasErrors() ? 'yes' : 'no'));
        if($parser->hasErrors()){
            $output->writeln('Error message:');
            $output->writeln($parser->getErrorMessage());
        }
    }
}
