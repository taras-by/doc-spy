<?php

namespace App\Tests\Controller;

use App\Tests\ProjectTestCase;

class SourceControllerTest extends ProjectTestCase
{
    public function testListAction()
    {
        $this->logIn();
        $this->client->request('GET', '/sources');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());

        $this->logInAsAdmin();
        $this->client->request('GET', '/sources');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testShowAction()
    {
        $this->client->request('GET', '/source/1');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->client->request('GET', '/source/5');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->client->request('GET', '/source/12');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->logIn();
        $this->client->request('GET', '/source/1');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->client->request('GET', '/source/5');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->client->request('GET', '/source/12');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());

        $this->logInAsAdmin();
        $this->client->request('GET', '/source/1');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->client->request('GET', '/source/5');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->client->request('GET', '/source/12');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
