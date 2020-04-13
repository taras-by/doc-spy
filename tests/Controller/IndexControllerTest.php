<?php

namespace App\Tests\Controller;

use App\Tests\ProjectTestCase;

class IndexControllerTest extends ProjectTestCase
{
    public function testIndexAction()
    {
        $this->client->request('GET', '/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testFeedAction()
    {
        $this->client->request('GET', '/feed');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->logIn();
        $this->client->request('GET', '/feed');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
