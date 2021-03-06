<?php

namespace App\Tests\Controller\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccountControllerTest extends WebTestCase
{


    // Profile page must redirect anonymous user (302)
    public function testProfileNotLoggedUser()
    {
        $client = static::createClient();
        $client->request('GET', '/profile');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    // Profile page accept unverified user and display a warning
    public function testProfileUnverifiedUser(){
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user1@snowtricks');
        $client->loginUser($testUser);

        $client->request('GET', '/profile');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('.text-danger', 'Compte non activé');
        $this->assertStringNotContainsString('Nouvelle photo du profil', $client->getResponse()->getContent());
    }

    // Profile page accept verified user
    public function testProfileVerifiedUser(){
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('john@snowtricks');
        $client->loginUser($testUser);

        $client->request('GET', '/profile');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('.text-success', 'Compte activé');
        $this->assertStringContainsString('Nouvelle photo du profil', $client->getResponse()->getContent());
    }

    // Send verify email then redirect to profile page
    public function testResendVerifyEmail(){
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user1@snowtricks');


        $client->request('GET', '/resend-verify-email');
        $this->assertTrue($client->getResponse()->isRedirect('/login'));

        $client->loginUser($testUser);

        $client->request('GET', '/resend-verify-email');
        $this->assertTrue($client->getResponse()->isRedirect('/profile'));

        $client->followRedirect();
        $this->assertStringContainsString('Veuillez suivre le lien', $client->getResponse()->getContent());
    }

}
