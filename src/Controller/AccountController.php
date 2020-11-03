<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\ProfileType;
use App\Service\AccountsHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;


class AccountController extends AbstractController
{

    /**
     * @Route ("/profile", name="user_profile")
     * @IsGranted ("ROLE_USER")
     * @return Response
     */
    public function profile(){
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(ProfileType::class, $user);


        return $this->render("users/profile.html.twig", [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param AccountsHelper $accountsHelper
     * @return Response
     * @Route ("/resend-verify-email", name="app_resend_verify")
     * @IsGranted ("ROLE_USER")
     */
    public function resendVerifyEmail(AccountsHelper $accountsHelper){
        try {
            $accountsHelper->sendVerifyEmail($user = $this->getUser());
        } catch (TransportExceptionInterface $e) {
            $this->addFlash("error", "Une erreur est survenue lors de l'envoi du mail d'activation, veuillez recommencer plus tard.");
            return $this->redirectToRoute('user_profile');
        }
        $this->addFlash("success", "Veuillez suivre le lien qui vous a été envoyé par mail pour activer votre compte");

        return $this->redirectToRoute('user_profile');
    }
}