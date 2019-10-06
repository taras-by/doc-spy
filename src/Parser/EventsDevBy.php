<?php

namespace App\Parser;

use App\Entity\Item;

class EventsDevBy extends AbstractParser implements ParserInterface
{
    const NUMBER_PAGES = 3;

    /**
     * @throws \Exception
     */
    protected function parse(): void
    {
        foreach ($this->getUrls() as $url) {
            $this->parsePage($url);
        }
    }

    private function getUrls(): array
    {
        $urls = [];
        for ($i = 1; $i <= self::NUMBER_PAGES; $i++) {
            $urls[] = $this->source->getUrl() . "?page=" . $i;
        }
        return $urls;
    }

    /**
     * @param string $url
     * @throws \Exception
     */
    private function parsePage(string $url): void
    {
        $document = $this->getDomDocument($url);

        $finder = new \DomXPath($document);
        $itemNodes = $finder->query("//*[contains(@class, 'list-item-events')]/div[@class='item']");

        foreach ($itemNodes as $itemNode) {
            $titleNode = $finder->query("div/a[@class='title']", $itemNode)[0];
            $title = $titleNode->nodeValue ?? null;
            $link = $this->url($titleNode->getAttribute('href'));

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
    }

    private function getDataFromLinkToGoogleCalendar(string $link): array
    {
        parse_str($link, $data);
        return $data;
    }
}
