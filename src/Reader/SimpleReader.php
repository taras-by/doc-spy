<?php

namespace App\Reader;

class SimpleReader extends BaseReader implements ReaderInterface
{
    public function getContent(string $url): string
    {
        if ($cachedContent = $this->cacheService->getContent() and $this->cacheService->isEnabled()) {
            return $cachedContent;
        }

        $content = file_get_contents($url);

        $this->cacheService->putContent($content);
        return $content;
    }
}