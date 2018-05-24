<?php

namespace App\Command;

use App\Entity\Item;
use App\Entity\Source;

use App\Repository\SourceRepository;
use App\Service\Parser\ParserManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParserCheckCommand extends ContainerAwareCommand
{
    private $parserManager;

    public function __construct(ParserManager $parserManager)
    {
        $this->parserManager = $parserManager;
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
        $parserManager = $this->parserManager;
        $items = $parserManager->getItems($source);

        $formatter = $this->getHelper('formatter');

        /** @var Item $item */
        foreach ($items as $item) {
            $output->writeln('Title: ' . $item->getTitle());
            $output->writeln('Description: ' . $formatter->truncate($item->getDescription(), 50));
            $output->writeln('Link: ' . $item->getLink());
            $output->writeln('Id: ' . $item->getId());
            $output->writeln('Created at: ' . $item->getCreatedAt()->format(DATE_ATOM));
            $output->writeln('Published at: ' . $item->getPublishedAt()->format(DATE_ATOM));
            $output->writeln('Updated at: ' . $item->getUpdatedAt()->format(DATE_ATOM));
            $output->writeln('-----');
        }
        $output->writeln('Has errors: ' . ($parserManager->hasErrors() ? 'yes' : 'no'));
        $output->writeln('Need add count: ' . $parserManager->getNeedAddCount());
        $output->writeln('All count: ' . $parserManager->getAllCount());
    }

    private function getSourceRepository(): SourceRepository
    {
        return $this->getContainer()->get('doctrine')->getRepository(Source::class);
    }
}
