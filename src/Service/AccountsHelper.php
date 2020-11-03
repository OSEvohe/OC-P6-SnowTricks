<?php


namespace App\Service;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\VerifyEmail\Model\VerifyEmailSignatureComponents;
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
        $this->updatePassword($user);

        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            'registration_confirmation_route',
            $user->getId(),
            $user->getEmail()
        );

        $email = $this->prepareMailSigned($user, $signatureComponents, "Bienvenue Dans la communauté Snowtricks", "security/confirmation_email.html.twig");
        $this->mailer->send($email);
    }


    /**
     * @param User $user
     */
    public function updatePassword(User $user){
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            $user->getPlainPassword()
        ));

        $this->em->persist($user);
        $this->em->flush();
    }


    public function verify(User $user)
    {
        $user->addRole(User::USER_VERIFIED);
        $this->em->persist($user);
        $this->em->flush();
    }


    /**
     * @param User $user
     * @param VerifyEmailSignatureComponents $signatureComponents
     * @return TemplatedEmail
     */
    private function prepareMailSigned(User $user, VerifyEmailSignatureComponents $signatureComponents, string $subject, string $htmlTemplate): TemplatedEmail
    {
        $email = new TemplatedEmail();
        $email->to($user->getEmail());
        $email->sender('sebastien@o-pa.fr');
        $email->subject($subject);
        $email->htmlTemplate($htmlTemplate);
        $email->context(['signedUrl' => $signatureComponents->getSignedUrl()]);
        return $email;
    }
}