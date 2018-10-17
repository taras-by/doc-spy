<?php

namespace App\Parser;

use App\Entity\Item;
use App\Reader\ReaderInterface;
use Doctrine\Common\Collections\ArrayCollection;

class EventsDevBy extends BaseParser implements ParserInterface
{
    const NUMBER_PAGES = 3;

    /**
     * @var ReaderInterface $reader
     */
    private $reader;

    public function __construct(ReaderInterface $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @return ArrayCollection
     * @throws \Exception
     */
    public function getItems(): ArrayCollection
    {
        foreach ($this->getUrls() as $number => $url) {
            $this->parsePage($url, $number);
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

    private function parsePage(string $url,int $number): void
    {
        try {
            $content = $this->reader
                ->setSourceId($this->source->getId())
                ->setPageNumber($number)
                ->getContent($url);
            $document = $this->getDomDocumentFromContent($content);

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
}
