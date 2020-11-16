<?php

namespace App\Tests\Controller\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testIndex(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(10, $crawler->filter('.trick-item')->count());
    }

    public function testLoadMore(){
        $client = static::createClient();
        $client->request('GET', '/trick/load-more/1/10');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($client->getResponse()->getContent());
    }

}
