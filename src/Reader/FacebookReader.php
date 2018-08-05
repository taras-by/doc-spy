<?php

namespace App\Reader;

class FacebookReader implements ReaderInterface
{
    const PHANTOM_JS_CLOUD_API_URL = 'http://PhantomJScloud.com/api/browser/v2/%s/';

    /**
     * @var array
     */
    private $config = [];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getContent(string $url): string
    {
        $apiUrl = sprintf(self::PHANTOM_JS_CLOUD_API_URL, $this->config['key']);

        $payload = (object)[
            'url' => $url,
            'ignoreImages' => true,
            'renderType' => 'html',
            'scripts' => (object)[
                'domReady' => [
                    sprintf('document.getElementsByName("email")[0].value = "%s"', $this->config['email']),
                    sprintf('document.getElementsByName("pass")[0].value = "%s"', $this->config['password']),
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
}