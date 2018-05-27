<?php

namespace App\Service\Parser;

use App\Entity\Source;
use Doctrine\Common\Util\Inflector;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ParserManager
{
    CONST PARSERS_PATH = 'App\\Service\\Parser\\Parsers\\';

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
        return $this->container->get($this->getParserClass($source->getParser()))
            ->setSource($source);
    }

    private function getParserClass(string $parser): string
    {
        return self::PARSERS_PATH . Inflector::classify($parser);
    }
}