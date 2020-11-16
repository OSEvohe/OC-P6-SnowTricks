<?php

namespace App\Tests\Controller\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

class ResetPasswordControllerTest extends WebTestCase
{

    public function testCheckEmail()
    {
        $client = static::createClient();
        $client->request('GET', '/check-email');
        $this->assertTrue($client->getResponse()->isRedirect('/reset-password-request'));
    }

   public function testRequest()
    {
        $client = static::createClient();
        $client->request('GET', '/reset-password-request');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
