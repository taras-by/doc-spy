<?php

namespace App\Command;

use App\Entity\Source;
use App\Repository\SourceRepository;
use App\Service\Parser\ParserManager;
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
     * @var RegistryInterface
     */
    private $entityManager;

    /**
     * Current time with rounding to minutes
     *
     * @var \DateTime
     */
    private $now;

    public function __construct(ParserManager $parserManager, RegistryInterface $entityManager)
    {
        $this->parserManager = $parserManager;
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

        /** @var Source $source */
        foreach ($sources as $source) {
            $parser = $this->parserManager->getParser($source);
            $items = $parser->getItems();

            $output->writeln('Parsed: ' . $source->getName());
            $output->writeln('  Received items: ' . $parser->getAllCount() .
                ($parser->getNeedAddCount() ? '. <info>new items: ' . $parser->getNeedAddCount() . '</info>' : '')
            );

            if($parser->hasErrors()){
                $source->upErrorCount();
            }else{
                $source->setErrorCount(0);
                $source->setUpdatedAt(new \DateTime());
            }

            $nextUpdateTime = $this->getNextUpdateTime($source->getUpdateInterval(), $source->getErrorCount());
            $source->setUpdateOn($nextUpdateTime);

            foreach ($items as $item) {
                $this->entityManager->getManager()->persist($item);
            }
        }
       $this->entityManager->getManager()->flush();
    }

    private function getSourceRepository(): SourceRepository
    {
        return $this->getContainer()->get('doctrine')->getRepository(Source::class);
    }

    /**
     * Set next update time
     *
     * @param integer $updateInteval
     * @param integer $errorCount
     * @return \DateTime
     * @throws \Exception
     */
    private function getNextUpdateTime($updateInteval, $errorCount)
    {
        $now = clone $this->now;
        return $now->add(new \DateInterval('PT' . $updateInteval * ($errorCount + 1) . 'M'));
    }
}
