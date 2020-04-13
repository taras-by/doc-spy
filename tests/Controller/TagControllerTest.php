<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TagControllerTest extends WebTestCase
{
    public function testShowAction()
    {
        $client = static::createClient();
        $client->request('GET', '/tag/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
