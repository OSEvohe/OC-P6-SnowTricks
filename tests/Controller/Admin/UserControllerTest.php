<?php

namespace App\Tests\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{

    public function testUpdate()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('john@snowtricks');
        $userToDelete =  $userRepository->findOneByEmail('john@snowtricks');

        $url = '/_sntrks_admin/user/'.$userToDelete->getId().'/edit';

        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isRedirect('/login'));

        $client->loginUser($testUser);

        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isRedirect('/'));

        $testUser = $userRepository->findOneByEmail('Admin@snowtricks');
        $client->loginUser($testUser);
        $client->request('GET', $url);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

    public function testDelete()
    {

        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('john@snowtricks');
        $userToDelete =  $userRepository->findOneByEmail('john@snowtricks');

        $url = '/_sntrks_admin/user/'.$userToDelete->getId().'/delete';

        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isRedirect('/login'));

        $client->loginUser($testUser);

        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isRedirect('/'));

        $testUser = $userRepository->findOneByEmail('Admin@snowtricks');
        $client->loginUser($testUser);
        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isRedirect('/_sntrks_admin/user/'));
        $client->followRedirect();
        $this->assertStringContainsString('utilisateur a été supprimé', $client->getResponse()->getContent());


        $url = '/_sntrks_admin/user/'.$testUser->getId().'/delete';
        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isRedirect('/_sntrks_admin/user/'));
        $client->followRedirect();
        $this->assertStringContainsString('Un compte avec le role Admin ne peux être supprimé', $client->getResponse()->getContent());

    }

    public function testDeleteUserPhoto()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('john@snowtricks');
        $userToDelete =  $userRepository->findOneByEmail('john@snowtricks');

        $url = '/_sntrks_admin/user/'.$userToDelete->getId().'/delete-photo';

        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isRedirect('/login'));

        $client->loginUser($testUser);

        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isRedirect('/'));

        $testUser = $userRepository->findOneByEmail('Admin@snowtricks');
        $client->loginUser($testUser);
        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isRedirect('/_sntrks_admin/user/'.$userToDelete->getId().'/edit'));
    }

    public function testIndex()
    {
        $url = '/_sntrks_admin/user/';
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('john@snowtricks');

        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isRedirect('/login'));

        $client->loginUser($testUser);

        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isRedirect('/'));

        $testUser = $userRepository->findOneByEmail('Admin@snowtricks');
        $client->loginUser($testUser);
        $client->request('GET', $url);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
