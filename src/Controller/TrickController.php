<?php


namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\User;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @return Response
     */
    public function view(Request $request, Trick $trick): Response
    {
        if (!$trick) {
            throw $this->createNotFoundException('Cette figure n\'existe pas');
        }

        $commentForm = $this->createForm(CommentType::class);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            /** @var Comment $comment */
            $comment = $commentForm->getData();
            /** @var User $user */
            $user = $this->getUser();

            $comment->setTrick($trick);
            $comment->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', 'Votre commentaire a été ajouté');

            return $this->redirectToRoute('trick_detail', ['slug' => $trick->getSlug()]);
        }

        return $this->render('trick/view.html.twig', [
            'trick' => $trick,
            'commentForm' => $commentForm->createView()
        ]);
    }

    /**
     * Edit trick
     *
     * @Route ("/trick/{slug}/edit" , name="trick_edit", priority="2")
     *
     * @param string $slug
     * @return Response
     */
    public function edit(string $slug): Response
    {
        return $this->render('trick/edit.html.twig');
    }

    /**
     * Add trick
     *
     * @Route ("/trick/add", name="trick_add", priority="3")
     *
     */
    public function add(): Response
    {
        return $this->render('trick/add.html.twig');
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
}