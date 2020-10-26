<?php


namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\TrickMedia;
use App\Form\CommentType;
use App\Form\CoverType;
use App\Form\TrickMediaImageType;
use App\Form\TrickMediaVideoType;
use App\Form\TrickType;
use App\Service\ImageUploader;
use App\Service\ManageTrick;
use App\Service\YoutubeHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    /**
     * View single trick
     *
     * @Route ("/trick/{slug}" , name="trick_detail")
     * @param Request $request
     * @param Trick $trick
     * @param YoutubeHelper $youtubeHelper
     * @return Response
     */
    public function view(Request $request, Trick $trick, YoutubeHelper $youtubeHelper): Response
    {
        $this->checkTrickExists($trick);

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
     *
     * @param Trick $trick
     * @param Request $request
     * @param YoutubeHelper $youtubeHelper
     * @param ManageTrick $manageTrickDatabase
     * @param ImageUploader $imageUploader
     * @return Response
     */
    public function edit(Trick $trick, Request $request, YoutubeHelper $youtubeHelper, ManageTrick $manageTrickDatabase, ImageUploader $imageUploader): Response
    {
        $trickCover = $this->createForm(CoverType::class, $trick);
        $trickMediaImageForm = $this->createForm(TrickMediaImageType::class);
        $trickMediaVideoForm = $this->createForm(TrickMediaVideoType::class);
        $form = $this->createForm(TrickType::class, $trick);

        /**
         * Only basic trick information with this form, Medias are handled separately
         */
        $form
            ->remove('trickMediaPicture')
            ->remove('trickMediaVideo');

        $trickCover->handleRequest($request);
        $trickMediaImageForm->handleRequest($request);
        $trickMediaVideoForm->handleRequest($request);
        $form->handleRequest($request);


        if ($this->processUpdateForm($trick, $form, 'Les informations de base du trick ont été modifiées', $manageTrickDatabase) ||
            $this->processUpdateForm($trick, $trickCover, 'L\'image principale a été modifiée', $manageTrickDatabase) ||
            $this->processUpdateForm($trick, $trickMediaImageForm, 'Une image a été ajoutée au trick', $manageTrickDatabase, $imageUploader) ||
            $this->processUpdateForm($trick, $trickMediaVideoForm, 'Une vidéo a été ajoutée au trick', $manageTrickDatabase)
        ) {
            return $this->redirectToRoute('trick_detail', ['slug' => $trick->getSlug()]);
        }


        return $this->render('trick/edit.html.twig', [
            'form' => $form->createView(),
            'formVideo' => $trickMediaVideoForm->createView(),
            'formImage' => $trickMediaImageForm->createView(),
            'formCover' => $trickCover->createView(),
            'trick' => $trick,
            'youtube' => $youtubeHelper,
        ]);
    }

    /**
     * Add trick
     *
     * @Route ("/trick/add", name="trick_add", priority="3")
     * @param Request $request
     * @param ManageTrick $manageTrickDatabase
     * @param ImageUploader $imageUploader
     * @return Response
     */
    public function add(Request $request, ManageTrick $manageTrickDatabase, ImageUploader $imageUploader): Response
    {
        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setUser($this->getUser());
            $manageTrickDatabase->addCover($form, $imageUploader);
            $manageTrickDatabase->addTrickMediaCollection($form, $imageUploader);
            $manageTrickDatabase->update($form);

            $this->addFlash('success', 'Le Trick a été crée');
            return $this->redirectToRoute('trick_detail', ['slug' => $trick->getSlug()]);
        }

        return $this->render('trick/add.html.twig', ['form' => $form->createView()]);
    }


    /**
     *
     * @Route ("trick/{slug}/delete", name="trick_delete", priority="2", methods={"POST"})
     *
     * @param string $slug
     * @return Response
     */
    public function delete(string $slug): Response
    {

    }

    private function checkTrickExists($trick)
    {
        if (!$trick) {
            throw $this->createNotFoundException('Cette figure n\'existe pas');
        }
    }


    private function processUpdateForm(Trick $trick, FormInterface $form, string $flash, ManageTrick $manageTrickDatabase, ImageUploader $imageUploader = null)
    {
        if ($form->isSubmitted() && $form->isValid()) {

            if ($imageUploader){
                $manageTrickDatabase->addUploadedTrickMediaImage($trick, $form, $imageUploader);
            } elseif (get_class($form->getConfig()->getType()->getInnerType()) == TrickMediaVideoType::class) {
                $trick->addTrickMedium($form->getData());
            } else {
                $trick = $form->getData();
            }

            $trick->addContributor($this->getUser());
            $manageTrickDatabase->update($trick);

            $this->addFlash('success', $flash);
            return true;
        }

        return false;
    }
}