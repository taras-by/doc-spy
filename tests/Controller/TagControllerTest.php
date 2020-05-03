<?php

namespace App\Tests\Controller;

use App\Tests\ProjectTestCase;

class TagControllerTest extends ProjectTestCase
{
    public function testItems()
    {
        $this->client->request('GET', '/tag/1');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->client->request('GET', '/tag/2');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->logIn();
        $this->client->request('GET', '/tag/2');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());

        $this->logInAsAdmin();
        $this->client->request('GET', '/tag/2');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testList()
    {
        $this->client->request('GET', '/tags');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->logIn();
        $this->client->request('GET', '/tags');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());

        $this->logInAsAdmin();
        $this->client->request('GET', '/tags');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testNew()
    {
        $this->client->request('GET', '/tag/new');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->client->request('POST', '/tag/new');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->logIn();
        $this->client->request('GET', '/tag/new');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
        $this->client->request('POST', '/tag/new');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());

        $this->logInAsAdmin();
        $this->client->request('GET', '/tag/new');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $this->client->request('POST', '/tag/new');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testShow()
    {
        $this->client->request('GET', '/tag/1/show');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->logIn();
        $this->client->request('GET', '/tag/1/show');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());

        $this->logInAsAdmin();
        $this->client->request('GET', '/tag/1/show');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testEdit()
    {
        $this->client->request('GET', '/tag/1/edit');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->client->request('POST', '/tag/1/edit');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->logIn();
        $this->client->request('GET', '/tag/1/edit');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
        $this->client->request('POST', '/tag/1/edit');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());

        $this->logInAsAdmin();
        $this->client->request('GET', '/tag/1/edit');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->client->request('POST', '/tag/1/edit');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testDelete()
    {
        $this->client->request('DELETE', '/tag/1');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->logIn();
        $this->client->request('DELETE', '/tag/1');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());

        $this->logInAsAdmin();
        $this->client->request('DELETE', '/tag/1');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }
}
