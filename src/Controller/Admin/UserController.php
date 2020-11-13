<?php


namespace App\Controller\Admin;


use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\User;
use App\Form\ProfileType;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use App\Service\ImageUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/_sntrks_admin/user", name="app_admin_user_")
 */
class UserController extends AbstractController
{

    /**
     * @Route("/", name="index")
     *
     * @param UserRepository $repository
     * @return Response
     */
    public function index(UserRepository $repository): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $repository->findAll()
        ]);
    }


    /**
     * @Route ("/{id}/edit", name="update")
     * @param Request $request
     * @param User $user
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function update(Request $request, User $user, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProfileType::class);
        $form->remove('photo');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($displayName = $form->get('displayName')->getData()) {
                $user->setDisplayName($displayName);
            }

            if ($email = $form->get('email')->getData()) {
                $user->setEmail($email);
            }


            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'L\'utilisateur a été modifié');
            return $this->redirectToRoute('app_admin_user_index');
        }

        return $this->render('admin/user/update.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route ("/{id}/delete", name="delete")
     * @param User $user
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function delete(User $user, EntityManagerInterface $em): RedirectResponse
    {
        if (false === array_search(USER::USER_ADMIN, $user->getRoles())) {
            // transfer tricks ownership to the current logged admin user
            foreach ($user->getTricks() as $trick){
                $trick->setUser($this->getUser());
            }


            $em->remove($user);
            $em->flush();
            $this->addFlash('success', 'L\'utilisateur a été supprimé');
        } else {
            $this->addFlash('error', 'Un compte avec le role Admin ne peux être supprimé');
        }

        return $this->redirectToRoute('app_admin_user_index');
    }

    /**
     * @Route ("/{id}/delete-photo", name="delete_photo")
     *
     * @param User $user
     * @param EntityManagerInterface $em
     * @param ImageUploader $imageUploader
     * @param ParameterBagInterface $parameterBag
     * @return RedirectResponse
     */
    public function deleteUserPhoto(User $user, EntityManagerInterface $em, ImageUploader $imageUploader, ParameterBagInterface $parameterBag): RedirectResponse
    {
        if ($user->getPhoto()) {
            $imageUploader->setTargetDirectory($parameterBag->get('kernel.project_dir') . '/public/uploads/profiles');
            $imageUploader->deleteFile($user->getPhoto());
        }

        $user->setPhoto(null);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('app_admin_user_update', ['id' => $user->getId()]);
    }
}