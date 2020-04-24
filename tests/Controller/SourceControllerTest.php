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

    public function testItems()
    {
        $this->client->request('GET', '/source/1');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->client->request('GET', '/source/4');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->client->request('GET', '/source/5');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->client->request('GET', '/source/12');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->logIn();
        $this->client->request('GET', '/source/1');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->client->request('GET', '/source/4');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
        $this->client->request('GET', '/source/5');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->client->request('GET', '/source/12');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());

        $this->logInAsAdmin();
        $this->client->request('GET', '/source/1');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->client->request('GET', '/source/4');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->client->request('GET', '/source/5');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->client->request('GET', '/source/12');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testClean()
    {
        $this->client->request('DELETE', '/source/5/clean');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->logIn();
        $this->client->request('DELETE', '/source/5/clean');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());

        $this->logInAsAdmin();
        $this->client->request('DELETE', '/source/5/clean');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testDelete()
    {
        $this->client->request('DELETE', '/source/20');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->logIn();
        $this->client->request('DELETE', '/source/20');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());

        $this->logInAsAdmin();
        $this->client->request('DELETE', '/source/20');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdate()
    {
        $this->client->request('POST', '/source/20/update');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->logIn();
        $this->client->request('POST', '/source/20/update');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());

        $this->logInAsAdmin();
        $this->client->request('POST', '/source/20/update');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testCheck()
    {
        $this->client->request('GET', '/source/20/check');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->logIn();
        $this->client->request('GET', '/source/20/check');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());

        $this->logInAsAdmin();
        $this->client->request('GET', '/source/20/check');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function checkFormCheck()
    {
        $this->client->request('POST', '/source/form/check');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->logIn();
        $this->client->request('POST', '/source/form/check');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());

        $this->logInAsAdmin();
        $this->client->request('POST', '/source/form/check');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testShow()
    {
        $this->client->request('GET', '/source/20/show');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->logIn();
        $this->client->request('GET', '/source/20/show');
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());

        $this->logInAsAdmin();
        $this->client->request('GET', '/source/20/show');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
