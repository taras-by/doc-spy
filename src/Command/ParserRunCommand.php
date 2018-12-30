<?php

namespace App\Command;

use App\Entity\Source;
use App\Repository\SourceRepository;
use App\Service\ParserHandler;
use App\Service\ParserManager;
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
     * @var ParserHandler
     */
    private $parserHandler;

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

    /**
     * ParserRunCommand constructor.
     * @param ParserManager $parserManager
     * @param ParserHandler $parserHandler
     * @param RegistryInterface $entityManager
     * @throws \Exception
     */
    public function __construct(ParserManager $parserManager, ParserHandler $parserHandler, RegistryInterface $entityManager)
    {
        $this->parserManager = $parserManager;
        $this->parserHandler = $parserHandler;
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
        $parserHandler = $this->parserHandler;

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
    }

    private function getSourceRepository(): SourceRepository
    {
        return $this->getContainer()->get('doctrine')->getRepository(Source::class);
    }
}
