<?php

namespace App\Command;

use App\Entity\Source;
use App\Repository\SourceRepository;
use App\Service\ParserService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParserRunCommand extends ContainerAwareCommand
{
    private $parser;

    /**
     * ParserRunCommand constructor.
     * @param $parser
     */
    public function __construct(ParserService $parser)
    {
        $this->parser = $parser;

        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setName('parser:run')
            ->setDescription('Parse document')
            ->addArgument('results', InputArgument::OPTIONAL, 'Count of Sources for parsing');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $results = $input->getArgument('results');

        /** @var SourceRepository $sourceRepository */
        $sourceRepository = $this->getContainer()->get('doctrine')->getRepository(Source::class);
        $sources = $sourceRepository->findForUpdate($results);

        /** @var Source $source */
        foreach ($sources as $source) {

            $this->parser->read($source);

            $output->writeln('Parsed: ' . $source->getName());
            $output->writeln('  Received items: ' . $this->parser->getAllCount() .
                ($this->parser->getAddedCount() ? '. <info>new items: ' . $this->parser->getAddedCount() . '</info>' : '')
            );
        }

    }
}
