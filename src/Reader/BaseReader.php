<?php

namespace App\Reader;

use App\Service\ReaderCacheService;

class BaseReader
{
    /**
     * @var ReaderCacheService
     */
    protected $cacheService;

    public function __construct(ReaderCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    protected function getKeyByUrl(string $url)
    {
        return urlencode($url);
    }
}