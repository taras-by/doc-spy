<?php

namespace App\Service;

use App\Entity\Source;
use App\Parser\ParserInterface;
use App\Reader\ReaderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ParserManager
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getParser(Source $source): ParserInterface
    {
        $parserConfig = ParserConfig::create($source);

        /** @var ParserInterface $parser */
        $parser = $this->container->get($parserConfig->getName());
        $parser->setSource($source);

        // if Reader was injected through the Parser constructor
        if ($parser->getReader() == null) {
            /** @var ReaderInterface $reader */
            $reader = $this->container->get($parserConfig->getReaderConfig()->getName());
            $parser->setReader($reader);
        }

        return $parser;
    }
}