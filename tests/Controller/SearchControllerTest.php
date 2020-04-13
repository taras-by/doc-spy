<?php

namespace App\Tests\Controller;

use App\Tests\ProjectTestCase;

class SearchControllerTest extends ProjectTestCase
{
    public function testIndexAction()
    {
        $this->client->request('GET', '/search?q=php');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
