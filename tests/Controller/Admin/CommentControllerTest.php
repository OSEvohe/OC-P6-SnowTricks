<?php

namespace App\Tests\Controller\Admin;

use App\Entity\Comment;
use App\Entity\User;
use App\Repository\TrickRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CommentControllerTest extends WebTestCase
{

    public function testIndex()
    {
        $url = '/_sntrks_admin/comment/';
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

    public function testDelete()
    {

        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('john@snowtricks');

        $comment = new Comment();
        $comment->setContent('test');
        $trick = static::$container->get(TrickRepository::class)->findOneBy([]);
        $comment->setTrick($trick);
        $comment->setUser($testUser);
        static::$container->get(EntityManagerInterface::class)->persist($trick);
        static::$container->get(EntityManagerInterface::class)->persist($comment);
        static::$container->get(EntityManagerInterface::class)->flush();

        $url = '/_sntrks_admin/comment/'.$comment->getId().'/delete';

        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isRedirect('/login'));

        $client->loginUser($testUser);

        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isRedirect('/'));

        $testUser = $userRepository->findOneByEmail('Admin@snowtricks');
        $client->loginUser($testUser);
        $client->request('GET', $url);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertStringContainsString('Le commentaire a été supprimé', $client->getResponse()->getContent());

    }

    public function testRead()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('john@snowtricks');

        $comment = new Comment();
        $comment->setContent('test azerty');
        $trick = static::$container->get(TrickRepository::class)->findOneBy([]);
        $comment->setTrick($trick);
        $comment->setUser($testUser);
        static::$container->get(EntityManagerInterface::class)->persist($trick);
        static::$container->get(EntityManagerInterface::class)->persist($comment);
        static::$container->get(EntityManagerInterface::class)->flush();

        $url = '/_sntrks_admin/comment/detail/'.$comment->getId();

        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isRedirect('/login'));

        $client->loginUser($testUser);

        $client->request('GET', $url);
        $this->assertTrue($client->getResponse()->isRedirect('/'));

        $testUser = $userRepository->findOneByEmail('Admin@snowtricks');
        $client->loginUser($testUser);
        $client->request('GET', $url);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('test azerty', $client->getResponse()->getContent());
    }
}
