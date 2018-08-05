<?php

namespace App\Service;

use App\Entity\Source;
use App\Parser\ParserInterface;
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
        /** @var ParserInterface $parser */
        return $this->container->get($source->getParser())
            ->setSource($source);
    }
}