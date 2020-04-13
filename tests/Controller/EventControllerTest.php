<?php

namespace App\Tests\Controller;

use App\Tests\ProjectTestCase;

class EventControllerTest extends ProjectTestCase
{
    public function testIndexAction()
    {
        $this->client->request('GET', '/events');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
