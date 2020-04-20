<?php

namespace App\Twig;

use App\Service\ParsersParameters;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ParserExtension extends AbstractExtension
{
    /**
     * @var ParsersParameters
     */
    private $parameters;

    public function __construct(ParsersParameters $parameters)
    {
        $this->parameters = $parameters;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('get_parser_label', [$this, 'getParserLabel'], [
                'is_safe' => ['html']
            ])
        );
    }

    /**
     * @param string $parserId
     * @return string
     */
    public function getParserLabel(string $parserId): string
    {
        return $this->parameters->getParserLabel($parserId);
    }
}
