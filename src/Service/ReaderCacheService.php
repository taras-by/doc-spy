<?php

namespace App\Service;

class ReaderCacheService
{
    private $cacheDir;

    private $isEnabled;

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
        return sprintf('%s/lastContent.html', $this->cacheDir);
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }
}