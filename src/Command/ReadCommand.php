<?php

namespace App\CoreBundle\Command;

use CoreBundle\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ReadCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('read')
            ->setDescription('Read last items');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $items = $this->getContainer()->get('doctrine')->getRepository(Item::class)->findLast();

        foreach ($items as $item) {
            $output->writeln("\n" . $item['publishedAt']->format('d.m.Y'));
            $output->writeln($item['title']);
            $output->writeln('<info>' . trim(strip_tags($item['description'])) . '</info>');
            $output->writeln($item['link']);
            $output->writeln('----------');
        }
    }
}
