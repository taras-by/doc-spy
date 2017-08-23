<?php

namespace ParserBundle\Command;

use CoreBundle\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ParserRunCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('parser:run')
            ->setDescription('Parse document')
            ->addArgument('url', InputArgument::REQUIRED, 'Document URL')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $input->getArgument('url');

        $this->getContainer()->get('doctrine')->getRepository(Item::class)->deleteAll();

        $feedIo = $this->getContainer()->get('feedio');
        $feed = $feedIo->read($url)->getFeed();
        foreach ($feed as $item) {

            $product = new Item;
            $product->setTitle($item->getTitle());
            $product->setDescription($item->getDescription());

            $em = $this->getContainer()->get('doctrine')->getManager();

            $em->persist($product);
            $em->flush();

            $output->writeln("\n".$item->getTitle());
            $output->writeln('<info>'.trim(strip_tags($item->getDescription())).'</info>');
            $output->writeln('<info>----------</info>');
        }
    }
}
