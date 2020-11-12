<?php


namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\TrickMedia;
use App\Form\CommentType;
use App\Form\CoverType;
use App\Form\MediaType;
use App\Form\TrickType;
use App\Service\ImageUploader;
use App\Service\ManageTrick;
use App\Service\YoutubeHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    const REDIRECT_POST_EDIT = 'trick_edit';

    /**
     * View single trick
     *
     * @Route ("/trick/{slug}" , name="trick_detail")
     * @param Request $request
     * @param Trick $trick
     * @param YoutubeHelper $youtubeHelper
     * @return Response
     */
    public function read(Request $request, Trick $trick, YoutubeHelper $youtubeHelper): Response
    {
        if (!$trick) {
            throw $this->createNotFoundException('Cette figure n\'existe pas');
        }

        $commentForm = $this->createForm(CommentType::class);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            /** @var Comment $comment */
            $comment = $commentForm->getData();

            $comment->setTrick($trick);
            $comment->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', 'Votre commentaire a été ajouté');
            return $this->redirectToRoute('trick_detail', ['slug' => $trick->getSlug()]);
        }

        return $this->render('trick/view.html.twig', [
            'trick' => $trick,
            'commentForm' => $commentForm->createView(),
            'youtube' => $youtubeHelper,
        ]);
    }

    /**
     * Edit trick
     *
     * @Route ("/trick/{slug}/edit" , name="trick_edit", priority="2")
     * @IsGranted("ROLE_USER_VERIFIED")
     *
     * @param Trick $trick
     * @param Request $request
     * @param YoutubeHelper $youtubeHelper
     * @param ManageTrick $manageTrick
     * @param ImageUploader $imageUploader
     * @return Response
     */
    public function update(Trick $trick, Request $request, YoutubeHelper $youtubeHelper, ManageTrick $manageTrick, ImageUploader $imageUploader): Response
    {
        if (!$trick) {
            throw $this->createNotFoundException('Cette figure n\'existe pas');
        }

        $trickCover = $this->createForm(CoverType::class, $trick);
        $formMedia = $this->createForm(MediaType::class, null, ['new' => true]);
        $form = $this->createForm(TrickType::class, $trick);

        /** Cover is handled separately */
        $form->remove('cover');

        $trickCover->handleRequest($request);
        $form->handleRequest($request);
        $formMedia->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Trick $trick */
            $trick = $form->getData();
            $manageTrick->update($trick);

            $this->addFlash('success', 'Le trick a été modifié');
            return $this->redirectToRoute(self::REDIRECT_POST_EDIT, ['slug' => $trick->getSlug()]);
        }

        if ($formMedia->isSubmitted() && $formMedia->isValid()) {
            if (!$formMedia->get('image')->isEmpty()) {
                $manageTrick->addUploadedTrickMediaImage($trick, $formMedia, $imageUploader);
            } else {
                /** @var TrickMedia $trickMedia */
                $trickMedia = $formMedia->getData();
                $trickMedia->setAlt($youtubeHelper->getVideoInfo($trickMedia->getContent())->title);
                $trick->addTrickMedium($formMedia->getData());
            }
                $manageTrick->update($trick);

                $this->addFlash('success', 'Un media a été ajouté au trick');
                return $this->redirectToRoute(self::REDIRECT_POST_EDIT, ['slug' => $trick->getSlug()]);
        }

        if ($trickCover->isSubmitted() && $trickCover->isValid()) {
            /** @var Trick $trick */
            $trick = $trickCover->getData();
            $manageTrick->update($trick);

            return $this->redirectToRoute(self::REDIRECT_POST_EDIT, ['slug' => $trick->getSlug()]);
        }


        return $this->render('trick/edit.html.twig', [
            'form' => $form->createView(),
            'formMedia' => $formMedia->createView(),
            'formCover' => $trickCover->createView(),
            'trick' => $trick,
            'youtube' => $youtubeHelper,
        ]);
    }

    /**
     * Add trick
     *
     * @Route ("/trick/add", name="trick_add", priority="3")
     * @IsGranted("ROLE_USER_VERIFIED")
     * @param Request $request
     * @param ManageTrick $manageTrickDatabase
     * @param ImageUploader $imageUploader
     * @return Response
     */
    public function create(Request $request, ManageTrick $manageTrickDatabase, ImageUploader $imageUploader): Response
    {
        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick, ['new' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setUser($this->getUser());
            $manageTrickDatabase->addCover($form, $imageUploader);
            $manageTrickDatabase->addTrickMediaCollection($form, $imageUploader);
            $manageTrickDatabase->update($trick);

            $this->addFlash('success', 'Le Trick a été crée');
            return $this->redirectToRoute('trick_detail', ['slug' => $trick->getSlug()]);
        }

        return $this->render('trick/add.html.twig', ['form' => $form->createView()]);
    }


    /**
     *
     * @Route ("trick/{slug}/delete", name="trick_delete", priority="2", methods={"GET"})
     * @IsGranted("ROLE_USER_VERIFIED")
     * @IsGranted("TRICK_DELETE", subject="trick")
     *
     * @param Trick $trick
     * @param ImageUploader $imageUploader
     * @return Response
     */
    public function delete(Trick $trick, ImageUploader $imageUploader): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        foreach ($trick->getTrickMedia() as $media){
            if ($media->getType() == TrickMedia::MEDIA_TYPE_IMAGE){
                $imageUploader->deleteFile($media->getContent());
            }
        }

        $entityManager->remove($trick);

        $entityManager->flush();

        $this->addFlash('success', 'Trick supprimé');
        return $this->redirectToRoute('home');
    }

    /**
     * @Route ("/trick/{slug}/delete-denied" , name="trick_delete_denied")
     * @param Trick $trick
     * @return RedirectResponse
     */
    public function deleteDenied(Trick $trick): Response{

        $this->addFlash('error', 'Seul le créateur d\'un trick est autorisé à le supprimer');
        return $this->redirectToRoute('trick_detail', ['slug' => $trick->getSlug()]);
    }
}