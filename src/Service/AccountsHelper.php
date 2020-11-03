<?php


namespace App\Service;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class AccountsHelper
{
    /**
     * @var VerifyEmailHelperInterface
     */
    private $verifyEmailHelper;
    /**
     * @var MailerInterface
     */
    private $mailer;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * AccountsHelper constructor.
     * @param VerifyEmailHelperInterface $helper
     * @param MailerInterface $mailer
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface $em
     */
    public function __construct(VerifyEmailHelperInterface $helper, MailerInterface $mailer,  UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em)
    {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
        $this->passwordEncoder= $passwordEncoder;
        $this->em = $em;
    }

    public function register(User $user)
    {
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            $user->getPassword()
        ));

        $this->em->persist($user);
        $this->em->flush();

        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            'registration_confirmation_route',
            $user->getId(),
            $user->getEmail()
        );

        $email = new TemplatedEmail();
        $email->to($user->getEmail());
        $email->sender('sebastien@o-pa.fr');
        $email->subject("Bienvenue Dans la communauté Snowtricks");
        $email->htmlTemplate('security/confirmation_email.html.twig');
        $email->context(['signedUrl' => $signatureComponents->getSignedUrl()]);

        $this->mailer->send($email);
    }

    public function verify(User $user)
    {
        $user->addRole(User::USER_VERIFIED);
        $this->em->persist($user);
        $this->em->flush();
    }
}