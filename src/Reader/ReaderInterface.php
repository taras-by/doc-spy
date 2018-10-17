<?php

namespace App\Reader;

interface ReaderInterface
{
    public function getContent(string $url);

    public function setPageNumber(int $pageNumber): ReaderInterface;

    public function setSourceId(int $sourceId): ReaderInterface;
}