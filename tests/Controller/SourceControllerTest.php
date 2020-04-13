<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SourceControllerTest extends WebTestCase
{
    public function testListAction()
    {
        $client = static::createClient();
        $client->request('GET', '/sources');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testShowAction()
    {
        $client = static::createClient();
        $client->request('GET', '/source/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client = static::createClient();
        $client->request('GET', '/source/12');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}
