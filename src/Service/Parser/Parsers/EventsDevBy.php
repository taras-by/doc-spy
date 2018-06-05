<?php

namespace App\Service\Parser\Parsers;

use App\Entity\Item;
use App\Service\Parser\ParserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use App\Service\Parser\BaseParser;

class EventsDevBy extends BaseParser implements ParserInterface
{
    const NUMBER_PAGES = 3;

    /**
     * @return ArrayCollection
     * @throws \Exception
     */
    public function getItems(): ArrayCollection
    {
        foreach ($this->getUrls() as $url) {
            $this->parsePage($url);
        }
        return $this->items;
    }

    private function getUrls(): array
    {
        $urls = [];
        for ($i = 1; $i <= self::NUMBER_PAGES; $i++) {
            $urls[] = $this->source->getUrl() . "/?page=" . $i;
        }
        return $urls;
    }

    private function parsePage(string $url): void
    {
        try {
            $document = $this->getDomDocument($url);

            $finder = new \DomXPath($document);
            $itemNodes = $finder->query("//*[contains(@class, 'list-item-events')]/div[@class='item']");

            foreach ($itemNodes as $itemNode) {
                $titleNode = $finder->query("div/a[@class='title']", $itemNode)[0];
                $title = $titleNode->nodeValue ?? null;
                $link = $this->source->getUrl() . $titleNode->getAttribute('href');

                $descriptionNode = $finder->query("div/p", $itemNode)[0];
                $descriptionHtml = str_replace("\n", ' ', $descriptionNode->ownerDocument->saveHTML($descriptionNode));
                // Parse HTML: <p><time>text<time/>description</p>
                preg_match('/<\/time>(.+)<\/p>/', $descriptionHtml, $matches);
                $description = $matches[1] ?? null;

                // $idNode = $finder->query("div/div[@class='status-event']", $itemNode)[0];
                // $id = $idNode->getAttribute('id');

                $dateNode = $finder->query("div/ul[@class='list-gray']/li/a", $itemNode)[1];
                $googleCalendarLink = $dateNode->getAttribute('href');
                $data = $this->getDataFromLinkToGoogleCalendar($googleCalendarLink);

                list($startDate, $endDate) = explode('/', $data['dates']);
                $startDate = new \DateTime($startDate);
                $endDate = new \DateTime($endDate);

                $item = (new Item())
                    ->setTitle($title)
                    ->setDescription($description)
                    ->setlink($link)
                    ->setPublishedAt(new \DateTime())
                    ->setStartDate($startDate)
                    ->setEndDate($endDate)
                    ->setSource($this->source);
                $this->items->add($item);

                ++$this->count;
            }

        } catch (\Exception $exception) {
            $this->hasErrors = true;
//            throw $exception;
        }
    }

    private function getDataFromLinkToGoogleCalendar(string $link): array
    {
        parse_str($link, $data);
        return $data;
    }

    protected function getDomDocument($path)
    {
        $content = file_get_contents($path);
        $document = new \DOMDocument();

        libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="utf-8" ?>' . $content);
        libxml_use_internal_errors(false);

        return $document;
    }
}