<?php

namespace App\Service\Parser\Parsers;

use App\Entity\Item;
use App\Service\Parser\ParserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use App\Service\Parser\BaseParser;

class FacebookCom extends BaseParser implements ParserInterface
{
    const PHANTOM_JS_CLOUD_API_URL = 'http://PhantomJScloud.com/api/browser/v2/%s/';
    const DATE_FORMAT = '%s %s %s:%s, -3 hours';

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @return ArrayCollection
     * @throws \Exception
     */
    public function getItems(): ArrayCollection
    {
        try {
            $content = $this->getContent($this->source->getUrl());
            //file_put_contents('var/fb.html', $content);

            $document = $this->getDomDocumentFromContent($content);

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

            if($this->count == 0){
                $this->hasErrors = true;
            }

        } catch (\Exception $exception) {
            $this->hasErrors = true;
            //throw $exception;
        }

        return $this->items;
    }

    private function getContent(string $url): string
    {
        // return file_get_contents('var/fb.html');

        $apiUrl = sprintf(self::PHANTOM_JS_CLOUD_API_URL, $this->key);

        $payload = (object)[
            'url' => $url,
            'ignoreImages' => true,
            'renderType' => 'html',
            'scripts' => (object)[
                'domReady' => [
                    sprintf('document.getElementsByName("email")[0].value = "%s"', $this->email),
                    sprintf('document.getElementsByName("pass")[0].value = "%s"', $this->password),
                    'document.getElementById("login_form").submit()',
                    'setTimeout(function(){window.scrollBy(0,10000);},2000)',
                ]
            ]
        ];

        $options = [
            'http' => [
                'header' => "Content-type: application/json\r\n",
                'method' => 'POST',
                'content' => json_encode($payload)
            ]
        ];

        $context = stream_context_create($options);
        return file_get_contents($apiUrl, false, $context);
    }

    private function getDate(string $month, string $day, string $hour = '00', string $minute = '00'): \DateTime
    {
        return new \DateTime(sprintf(self::DATE_FORMAT, $month, $day, $hour, $minute));
    }

    public function clearUrl(string $url): string
    {
        $parsed_url = parse_url($url);
        return $parsed_url['scheme'] . '://' . $parsed_url['host'] . $parsed_url['path'];
    }

    /**
     * @param string $key
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}