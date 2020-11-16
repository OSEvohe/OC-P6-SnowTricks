<?php

namespace App\Tests\Controller\Admin;

use App\Repository\TrickGroupRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TrickGroupControllerTest extends WebTestCase
{
    /**
     * @dataProvider provideUrls
     * @param $url
     */
    public function testAccess($url){
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

    public function testUpdate(){
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('john@snowtricks');

        $trickGroup = static::$container->get(TrickGroupRepository::class)->findOneBy([]);
        $url = '/_sntrks_admin/trick-group/'.$trickGroup->getSlug().'/edit';

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

    public function testDelete(){
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('john@snowtricks');

        $trickGroup = static::$container->get(TrickGroupRepository::class)->findOneBy(['slug' => 'grabs']);
        $url = '/_sntrks_admin/trick-group/'.$trickGroup->getSlug().'/delete';

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
        $this->assertStringContainsString('Un groupe contenant des tricks ne peut être supprimé', $client->getResponse()->getContent());

        $trickGroup = static::$container->get(TrickGroupRepository::class)->findOneBy(['slug' => 'slides']);
        $client->request('GET', '/_sntrks_admin/trick-group/'.$trickGroup->getSlug().'/delete');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client->followRedirect();
        $this->assertStringContainsString('Le groupe a été supprimé', $client->getResponse()->getContent());

    }

    public function provideUrls()
    {

        return [
            ['/_sntrks_admin/trick-group/'],
            ['/_sntrks_admin/trick-group/create'],
            /*['/_sntrks_admin/trick-group/'.$trickGroup->getId().'/edit'],
            ['/_sntrks_admin/trick-group/'.$trickGroup->getId().'/delete'],*/
        ];
    }
}
