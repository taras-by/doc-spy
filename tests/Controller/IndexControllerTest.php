<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    public function testIndexAction()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testFeedAction()
    {
        $client = static::createClient();
        $client->request('GET', '/feed');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}
