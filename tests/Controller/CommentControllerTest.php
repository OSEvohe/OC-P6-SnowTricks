<?php

namespace App\Tests\Controller\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CommentControllerTest extends WebTestCase
{

    public function testLoadMore()
    {
        $client = static::createClient();
        $client->request('GET', '/comments/le-180/load-more/0/10');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($client->getResponse()->getContent());
    }
}
