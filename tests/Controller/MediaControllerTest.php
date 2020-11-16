<?php

namespace App\Tests\Controller\Controller;

use App\Entity\TrickMedia;
use App\Repository\TrickMediaRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MediaControllerTest extends WebTestCase
{

    public function testYoutubeVideoInfo()
    {
        $client = static::createClient();

        $client->request('GET', '/media/video-info-4JfBfQpG77o');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($client->getResponse()->getContent());

    }

    public function testDeleteCover()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('john@snowtricks');
        $client->loginUser($testUser);

        $client->request('GET', 'trick/le-180/cover/delete');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $client->request('GET', 'trick/bad-slug/cover/delete');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testDelete()
    {
        $client = static::createClient();

        $mediaRepository =  static::$container->get(TrickMediaRepository::class);
        $testMedia = $mediaRepository->findOneBy(['type' => TrickMedia::MEDIA_TYPE_VIDEO]);

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('john@snowtricks');

        $client->loginUser($testUser);


        $client->request('GET', '/media/'.$testMedia->getId().'/delete');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $client->request('GET', '/media/'.$testMedia->getId().'/delete');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
