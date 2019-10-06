<?php

namespace App\Parser;

use App\Entity\Item;
use App\Reader\ReaderInterface;

class FacebookCom extends AbstractParser implements ParserInterface
{
    const DATE_FORMAT = '%s %s %s:%s, -3 hours';

    public function __construct(ReaderInterface $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @throws \Exception
     */
    protected function parse(): void
    {
        $document = $this->getDomDocument($this->source->getUrl());

        $finder = new \DomXPath($document);
        $itemNodes = $finder->query("//div[contains(@class, '_5lqg _4-u2  _4-u8')]");

        setlocale(LC_TIME, 'ru_RU');

        foreach ($itemNodes as $itemNode) {

            $titleNode = $finder->query("div/div/div/div/div/div/div/div/a", $itemNode)[0];
            $title = $titleNode->nodeValue ?? null;
            $link = $this->url($titleNode->getAttribute('href'));
            $link = $this->clearUrl($link);

            $startDateNodes = $finder->query("div/div/div/div/div/span/span", $itemNode);
            $startMonth = (string)$startDateNodes[0]->nodeValue;
            $startMonth = $this->convertMonth($startMonth);
            $startDay = (string)$startDateNodes[1]->nodeValue;
            $startHour = '00';
            $startMinute = '00';

            $detailsNode = $finder->query("div/div/div/div/div/div/div/div/span", $itemNode)[0];
            $detailsString = str_replace(' ', ' ', $detailsNode->textContent);
            list($detailsDataString) = explode(' · ', $detailsString);

            // 'Sun 12:15' or 'Today at 18:30' or 'Mon 11:00 UTC+03'
            if (mb_ereg('^[\D]+ (\d{1,2}):(\d{1,2})', $detailsDataString, $matches)) {
                list(, $startHour, $startMinute) = $matches;
            }
            $startDate = $this->getDate($startMonth, $startDay, $startHour, $startMinute);

            $endDate = null;
            // '28 jun - 1 aug'
            if (mb_ereg('^(\d{1,2}) (\D{3}) - (\d{1,2}) (\D{3})$', $detailsDataString, $matches)) {
                list(, , , $endDay, $endMonth) = $matches;
                $endMonth = $this->convertMonth($endMonth);
                $endDate = $this->getDate($endMonth, $endDay);
            }

            $item = (new Item())
                ->setTitle($title)
//                    ->setDescription($description)
                ->setlink($link)
                ->setPublishedAt(new \DateTime())
                ->setStartDate($startDate)
                ->setEndDate($endDate)
                ->setSource($this->source);
            $this->items->add($item);

            ++$this->count;
        }
    }

    private function getDate(string $month, string $day, string $hour = '00', string $minute = '00'): \DateTime
    {
        return new \DateTime(sprintf(self::DATE_FORMAT, $month, $day, $hour, $minute));
    }
}