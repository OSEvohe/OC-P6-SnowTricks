<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TrickControllerTest extends WebTestCase
{
    public function testread()
    {
        $client = static::createClient();
        $client->request('GET', '/trick/le-180');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h1', 'Le 180');
    }


    public function testUpdate()
    {
        $client = static::createClient();
        $client->request('GET', '/trick/le-180/edit');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user1@snowtricks');
        $client->loginUser($testUser);
        $client->request('GET', '/trick/le-180/edit');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $testUser = $userRepository->findOneByEmail('john@snowtricks');
        $client->loginUser($testUser);
        $client->request('GET', '/trick/le-180/edit');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCreate()
    {
        $client = static::createClient();
        $client->request('GET', '/trick/add');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user1@snowtricks');
        $client->loginUser($testUser);
        $client->request('GET', '/trick/add');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $testUser = $userRepository->findOneByEmail('john@snowtricks');
        $client->loginUser($testUser);
        $client->request('GET', '/trick/add');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }


}