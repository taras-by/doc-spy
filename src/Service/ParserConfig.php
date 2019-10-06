<?php

namespace App\Service;

use App\Entity\Source;

class ParserConfig
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $parserOptions;

    /**
     * @var ReaderConfig
     */
    private $readerConfig;

    public static function create(Source $source): self
    {
        $readerOptions = [];
        $parserOptions = [];

        $readerConfig = (new ReaderConfig())
            ->setName('simple_reader')
            ->setOptions($readerOptions);

        $parserConfig = (new ParserConfig())
            ->setName($source->getParser())
            ->setParserOptions($parserOptions)
            ->setReaderConfig($readerConfig);

        return $parserConfig;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ParserConfig
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return array
     */
    public function getParserOptions(): array
    {
        return $this->parserOptions;
    }

    /**
     * @param array $parserOptions
     * @return ParserConfig
     */
    public function setParserOptions(array $parserOptions): self
    {
        $this->parserOptions = $parserOptions;

        return $this;
    }

    /**
     * @return ReaderConfig
     */
    public function getReaderConfig(): ReaderConfig
    {
        return $this->readerConfig;
    }

    /**
     * @param ReaderConfig $readerConfig
     * @return ParserConfig
     */
    public function setReaderConfig(ReaderConfig $readerConfig): self
    {
        $this->readerConfig = $readerConfig;

        return $this;
    }
}