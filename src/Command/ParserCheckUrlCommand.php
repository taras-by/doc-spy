<?php

namespace App\Command;

use App\Entity\Item;
use App\Entity\Source;
use App\Service\Parser\ParserInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParserCheckUrlCommand extends AbstractParserCheckCommand
{
    protected function configure()
    {
        $this
            ->setName('parser:check:url')
            ->setDescription('Check parser for url')
            ->addArgument('parserService', InputArgument::REQUIRED, 'Parser service')
            ->addArgument('url', InputArgument::REQUIRED, 'Url');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $input->getArgument('url');
        $parserService = $input->getArgument('parserService');

        $source = $this->createNewSource($parserService, $url);
        $parser = $this->parserManager->getParser($source);
        $items = $parser->getItems();

        /** @var Item $item */
        foreach ($items as $item) {
            $this->writeItem($item, $output);
        }
        $this->writeSummary($parser, $output);
    }

    protected function createNewSource(string $parserService, string $url): Source
    {
        $source = new Source();
        return $source
            ->setParser($parserService)
            ->setUrl($url);
    }

    protected function writeSummary(ParserInterface $parser, OutputInterface $output): void
    {
        $output->writeln('Has errors: ' . ($parser->hasErrors() ? 'yes' : 'no'));
        $output->writeln('All count: ' . $parser->getCount());
    }
}
