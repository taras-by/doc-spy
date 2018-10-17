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

    /**
     * @param int $pageNumber
     * @return SimpleReader
     */
    public function setPageNumber(int $pageNumber): ReaderInterface
    {
        $this->cacheService->setPageNumber($pageNumber);

        return $this;
    }

    /**
     * @param int $sourceId
     * @return SimpleReader
     */
    public function setSourceId(int $sourceId): ReaderInterface
    {
        $this->cacheService->setSourceId($sourceId);

        return $this;
    }
}