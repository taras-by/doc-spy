<?php

namespace App\Service\Parser\Parsers;

use App\Entity\Item;
use App\Service\Parser\ParserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use App\Service\Parser\BaseParser;

class FacebookCom extends BaseParser implements ParserInterface
{
    const PHANTOM_JS_CLOUD_API_URL = 'http://PhantomJScloud.com/api/browser/v2/%s/';

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

                $dateNodes = $finder->query("div/div/div/div/div/span/span", $itemNode);
                $month = $dateNodes[0]->nodeValue;
                $month = $this->convertMonth((string)$month);
                $day = $dateNodes[1]->nodeValue;
                $date = new \DateTime(sprintf('%s %s, -3 hours', $day, $month));

                $item = (new Item())
                    ->setTitle($title)
//                    ->setDescription($description)
                    ->setlink($link)
                    ->setPublishedAt(new \DateTime())
                   ->setStartDate($date)
                   ->setEndDate($date)
                    ->setSource($this->source);
                $this->items->add($item);

                ++$this->count;
            }

        } catch (\Exception $exception) {
            $this->hasErrors = true;
            throw $exception;
        }

        return $this->items;
    }

    private function getContent(string $url): string
    {
        // return file_get_contents('var/fb.html');

        $apiUrl = sprintf(self::PHANTOM_JS_CLOUD_API_URL, $this->key);

        $payload = (object)[
            'url' => $url,
            'renderType' => 'html',
            'scripts' => (object)[
                'domReady' => [
                    sprintf('document.getElementsByName("email")[0].value = "%s"', $this->email),
                    sprintf('document.getElementsByName("pass")[0].value = "%s"', $this->password),
                    'document.getElementById("login_form").submit()',
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