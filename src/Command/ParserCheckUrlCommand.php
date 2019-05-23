<?php

namespace App\Command;

use App\Entity\Item;
use App\Entity\Source;
use App\Parser\ParserInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
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
            ->addArgument('url', InputArgument::REQUIRED, 'Url')
            ->addOption(
                'create',
                null,
                InputOption::VALUE_OPTIONAL,
                'Should I yell while greeting?',
                false
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $create = ($input->getOption('create') !== false);

        $url = $input->getArgument('url');
        $parserService = $input->getArgument('parserService');

        $source = $this->createNewSource($parserService, $url);
        $parser = $this->parserManager->getParser($source);
        $parser->run();
        $items = $parser->getItems();

        /** @var Item $item */
        foreach ($items as $item) {
            $this->writeItem($item, $output);
        }
        $this->writeSummary($parser, $output);

        if ($create && !$parser->hasErrors()) {
            $this->entityManager->getManager()->persist($source);
            $this->entityManager->getManager()->flush();
            $output->writeln(sprintf('The resource with id = %s was successfully created!', $source->getId()));
        }
    }

    protected function createNewSource(string $parserService, string $url): Source
    {
        $source = new Source();
        return $source
            ->setName(parse_url($url, PHP_URL_HOST))
            ->setParser($parserService)
            ->setIcon('/favicon.ico')
            ->setVisibility(Source::VISIBILITY_PUBLIC)
            ->setUrl($url);
    }

    protected function writeSummary(ParserInterface $parser, OutputInterface $output): void
    {
        $output->writeln('Has errors: ' . ($parser->hasErrors() ? 'yes' : 'no'));
        $output->writeln('All count: ' . $parser->getCount());
    }
}
