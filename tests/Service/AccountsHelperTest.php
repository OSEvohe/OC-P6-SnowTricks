<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Service\AccountsHelper;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class AccountsHelperTest extends TestCase
{
    /**
     * @var AccountsHelper
     */
    private $accountHelper;
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $helper;
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $mailer;
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $passwordEncoder;
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $em;

    /** VerifyEmailHelperInterface $helper, MailerInterface $mailer, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em */
    public function __construct()
    {
        $this->helper = $this->createMock(VerifyEmailHelperInterface::class);
        $this->mailer = $this->createMock(MailerInterface::class);
        $this->passwordEncoder = $this->createMock(UserPasswordEncoderInterface::class);
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->accountHelper = new AccountsHelper($this->helper, $this->mailer, $this->passwordEncoder, $this->em);
        parent::__construct();
    }

    public function testVerify()
    {
        $user = new User();
        $this->accountHelper->verify($user);

        $this->assertTrue(in_array(User::USER_VERIFIED,$user->getRoles()));
    }

    public function testSendVerifyEmail()
    {

    }
}
