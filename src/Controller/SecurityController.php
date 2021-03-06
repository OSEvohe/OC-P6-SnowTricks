<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRegistrationFormType;
use App\Service\AccountsHelper;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param AccountsHelper $accountsHelper
     * @return RedirectResponse|Response|null
     */
    public function register(Request $request, AccountsHelper $accountsHelper)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(UserRegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $accountsHelper->register($user = $form->getData());
            $this->addFlash('success', 'Bienvenue dans la communauté Snowtricks ' . $user->getDisplayName() . '! Votre compte a bien été créé, veuillez suivre le lien qui vous a été envoyé par mail pour l\'activer');
            return $this->redirectToRoute('home');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify", name="registration_confirmation_route")
     * @param Request $request
     * @param AccountsHelper $accountsHelper
     * @param VerifyEmailHelperInterface $verifyEmailHelper
     * @return Response
     */
    public function verifyUserEmail(Request $request, AccountsHelper $accountsHelper, VerifyEmailHelperInterface $verifyEmailHelper): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($this->isGranted('ROLE_USER_VERIFIED')){
            $this->addFlash('success', 'Votre compte est déjà activé !');
            return $this->redirectToRoute('home');
        }

        /** @var User $user */
        $user = $this->getUser();

        try {
            $verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());
        } catch (VerifyEmailExceptionInterface $e) {
            $this->addFlash('error','Le lien envoyé à votre adresse email semble invalide, veuillez recommencer');
            return $this->redirectToRoute('home');
        }

        $accountsHelper->verify($user);
        $this->addFlash('success', 'Votre adresse mail est verifiée, votre compte est actif.');
        return $this->redirectToRoute('home');
    }

    /**
     * Error page when user have to be verified
     * @Route ("/access-denied/unverified", name="app_denied_unverified")
     */
    public function unVerifiedUser(){
        return $this->render('security/denied_unverified.html.twig');
    }
}
