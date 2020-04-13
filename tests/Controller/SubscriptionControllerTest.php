<?php

namespace App\Tests\Controller;

use App\Tests\ProjectTestCase;

class SubscriptionControllerTest extends ProjectTestCase
{
    public function testIndexAction()
    {
        $this->client->request('GET', '/subscriptions');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        $this->logIn();
        $this->client->request('GET', '/subscriptions');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
