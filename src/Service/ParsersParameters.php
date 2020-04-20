<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ParsersParameters
{
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    /**
     * @return array
     */
    public function getParsers(): array
    {
        $parsers = $this->parameterBag->get('parsers');
        if ($this->parameterBag->has('custom_parsers')) {
            $parsers = $parsers + $this->parameterBag->get('custom_parsers');
        }
        return $parsers;
    }

    /**
     * @return array
     */
    public function getParserChoices(): array
    {
        return array_flip($this->getParsers());
    }

    /**
     * @param string $parserId
     * @return string
     */
    public function getParserLabel(string $parserId): string
    {
        return $this->getParsers()[$parserId];
    }
}
