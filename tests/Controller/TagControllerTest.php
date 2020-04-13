<?php

namespace App\Tests\Controller;

use App\Tests\ProjectTestCase;

class TagControllerTest extends ProjectTestCase
{
    public function testShowAction()
    {
        $this->client->request('GET', '/tag/1');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
