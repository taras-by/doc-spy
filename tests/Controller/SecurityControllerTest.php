<?php

namespace App\Tests\Controller;

use App\Tests\ProjectTestCase;

class SecurityControllerTest extends ProjectTestCase
{
    public function testResetPassword()
    {
        $this->client->request('GET', '/reset-password');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testLogout()
    {
        $this->client->request('GET', '/logout');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testLogin()
    {
        $this->client->request('GET', '/login');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
