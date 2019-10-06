<?php

namespace App\Reader;

class SimpleReader extends BaseReader implements ReaderInterface
{
    public function getContent(string $url): string
    {
        $key = $this->getKeyByUrl($url);

        if ($cachedContent = $this->cacheService->getContent($key) and $this->cacheService->isEnabled()) {
            return $cachedContent;
        }

        $content = file_get_contents($url);
        $this->cacheService->putContent($key, $content);

        return $content;
    }
}