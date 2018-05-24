<?php

namespace App\Service\Parser;

use App\Entity\Source;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Util\Inflector;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ParserManager implements ParserInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var int
     */
    private $needAddCount = 0;

    /**
     * @var int
     */
    private $allCount = 0;

    /**
     * @var bool
     */
    private $hasErrors = false;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getItems(Source $source): ArrayCollection
    {
        /** @var ParserInterface $parser */
        $parser = $this->container->get($this->getParserClass($source->getParser()));
        $items = $parser->getItems($source);
        $this->allCount = $parser->getAllCount();
        $this->needAddCount = $parser->getNeedAddCount();
        $this->hasErrors = $parser->hasErrors();
        return $items;
    }

    public function getAllCount(): int
    {
        return $this->allCount;
    }

    public function getNeedAddCount(): int
    {
        return $this->needAddCount;
    }

    public function hasErrors(): bool
    {
        return $this->hasErrors;
    }

    private function getParserClass(string $parser): string
    {
        return 'App\\Service\\Parser\\Parsers\\' . Inflector::classify($parser);
    }
}