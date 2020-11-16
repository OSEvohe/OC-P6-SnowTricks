<?php

namespace App\Tests\Controller\Admin;


use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{



    public function testIndex()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('Admin@snowtricks');

        $client->request('GET', '/profile');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $client->loginUser($testUser);

        $client->request('GET', '/profile');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
