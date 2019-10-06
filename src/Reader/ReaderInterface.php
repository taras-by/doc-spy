<?php

namespace App\Reader;

interface ReaderInterface
{
    public function getContent(string $url);
}