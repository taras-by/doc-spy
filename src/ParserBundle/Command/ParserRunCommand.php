<?php

namespace ParserBundle\Command;

use CoreBundle\Entity\Item;
use ParserBundle\Entity\Source;
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
            ->setDescription('Parse document');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $sourceRepository = $this->getContainer()->get('doctrine')->getRepository(Source::class);
        $itemRepository = $this->getContainer()->get('doctrine')->getRepository(Item::class);

        $source = $sourceRepository->findNextSource();

        $feedIo = $this->getContainer()->get('feedio');
        $feed = $feedIo->read($source->getUrl())->getFeed();

        $em = $this->getContainer()->get('doctrine')->getManager();

        foreach ($feed as $feedItem) {
            if (!$itemRepository->findByLink($feedItem->getLink())) {

                $item = new Item;
                $item->setTitle($feedItem->getTitle());
                $item->setDescription($feedItem->getDescription());
                $item->setlink($feedItem->getlink());
                $item->setPublishedAt($feedItem->getLastModified());
                $em->persist($item);

                $output->writeln("\n" . $feedItem->getTitle());
                $output->writeln('<info>' . trim(strip_tags($feedItem->getDescription())) . '</info>');
                $output->writeln('<info>----------</info>');
            }
        }

        $source->setUpdatedAt(new \DateTime());
        $em->flush();
    }
}
