<?php

namespace App\Service;

class ReaderCacheService
{
    private $cacheDir;

    private $isEnabled;

    /**
     * @var int
     */
    private $pageNumber = 0;

    /**
     * @var int
     */
    private $sourceId = 0;

    public function __construct(string $cacheDir, string $isEnabled)
    {
        $this->cacheDir = $cacheDir;
        $this->isEnabled = $isEnabled == 'true' ? true : false;
    }

    public function getContent(): ?string
    {
        $filePath = $this->getFilepath();

        if (file_exists($filePath)) {
            return file_get_contents($filePath);
        }

        return null;
    }

    public function putContent(string $content): void
    {
        file_put_contents($this->getFilepath(), $content);
    }

    private function getFilepath(): string
    {
        return sprintf('%s/%s-%s-page.html', $this->cacheDir, $this->sourceId, $this->pageNumber);
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    /**
     * @param int $pageNumber
     * @return ReaderCacheService
     */
    public function setPageNumber(int $pageNumber): ReaderCacheService
    {
        $this->pageNumber = $pageNumber;

        return $this;
    }

    /**
     * @param int $sourceId
     * @return ReaderCacheService
     */
    public function setSourceId(int $sourceId): ReaderCacheService
    {
        $this->sourceId = $sourceId;

        return $this;
    }
}