<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;

class ReaderCacheService
{
    private $cacheDir;

    private $isEnabled;

    public function __construct(string $cacheDir, string $isEnabled)
    {
        $this->cacheDir = $cacheDir;
        $this->isEnabled = $isEnabled == 'true' ? true : false;

        $filesystem = new Filesystem();
        if(!$filesystem->exists($this->cacheDir)){
            $filesystem->mkdir($this->cacheDir);
        }
    }

    public function getContent(string $key): ?string
    {
        $filePath = $this->getFilepath($key);

        if (file_exists($filePath)) {
            return file_get_contents($filePath);
        }

        return null;
    }

    public function putContent(string $key, string $content): void
    {
        file_put_contents($this->getFilepath($key), $content);
    }

    private function getFilepath(string $key): string
    {
        return sprintf('%s/%s-page.html', $this->cacheDir, $key);
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }
}