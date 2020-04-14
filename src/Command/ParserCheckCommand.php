<?php

namespace App\Command;

use App\Entity\Item;
use App\Entity\Source;
use App\Repository\SourceRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParserCheckCommand extends AbstractParserCheckCommand
{
    protected function configure()
    {
        $this
            ->setName('parser:check')
            ->setDescription('Check parser for source')
            ->addArgument('id', InputArgument::REQUIRED, 'Id of Source');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sourceId = $input->getArgument('id');

        /** @var Source $source */
        $source = $this->getSourceRepository()->find($sourceId);
        $parser = $this->parserManager->getParser($source);
        $parser->run();
        $items = $parser->getItems();

        /** @var Item $item */
        foreach ($items as $item) {
            $this->writeItem($item, $output);
        }
        $this->writeSummary($parser, $output);
    }

    private function getSourceRepository(): SourceRepository
    {
        return $this->getEntityManager()->getRepository(Source::class);
    }
}
