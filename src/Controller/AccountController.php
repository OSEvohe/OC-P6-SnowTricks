<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\ProfileType;
use App\Service\AccountsHelper;
use App\Service\ImageUploader;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;


class AccountController extends AbstractController
{

    /**
     * @Route ("/profile", name="user_profile")
     * @IsGranted ("ROLE_USER")
     * @param Request $request
     * @param ImageUploader $imageUploader
     * @param EntityManagerInterface $em
     * @param ParameterBagInterface $parameterBag
     * @return Response
     */
    public function profile(Request $request, ImageUploader $imageUploader, EntityManagerInterface $em, ParameterBagInterface $parameterBag)
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(ProfileType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($displayName = $form->get('displayName')->getData()) {
                $user->setDisplayName($displayName);
            }

            if ($uploadedFile = $form->get('photo')->getData()) {
                $imageUploader->setTargetDirectory($parameterBag->get('kernel.project_dir') . '/public/uploads/profiles');

                /* Delete previous photo if any before fetching form data */
                if ($user->getPhoto()) {
                    $imageUploader->deleteFile($user->getPhoto());
                }
                $user->setPhoto($imageUploader->upload($uploadedFile));
            }

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_profile');
        }


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
    public function resendVerifyEmail(AccountsHelper $accountsHelper)
    {
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