<?php

namespace App\Command;

use App\Entity\Item;
use App\Service\ParserManager;
use App\Parser\ParserInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

abstract class AbstractParserCheckCommand extends ContainerAwareCommand
{
    /**
     * @var \App\Service\ParserManager
     */
    protected $parserManager;

    /**
     * @var RegistryInterface
     */
    protected $entityManager;

    public function __construct(ParserManager $parserManager, RegistryInterface $entityManager)
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
        $output->writeln('Has errors: ' . ($parser->hasErrors() ? 'yes' : 'no'));
        $output->writeln('All count: ' . $parser->getCount());
    }
}
