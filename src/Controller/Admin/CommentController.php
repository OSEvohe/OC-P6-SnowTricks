<?php


namespace App\Controller\Admin;


use App\Entity\Comment;
use App\Entity\Trick;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/_sntrks_admin/comment", name="app_admin_comment_")
 */
class CommentController extends AbstractController
{

    /**
     *
     * @Route("/", name="index")
     * @Route ("/trick/{slug}", name="index_filterby_trick")
     *
     * @param CommentRepository $repository
     * @param Trick|null $trick
     * @return Response
     */
    public function index(CommentRepository $repository, Trick $trick = null): Response
    {
        if ($trick) {
            $comments = $trick->getComments();
        } else {
            $comments = $repository->findAll();
        }
        return $this->render('admin/comment/index.html.twig', [
            'comments' => $comments
        ]);
    }


    /**
     * @Route ("/detail/{id}", name="detail")
     * @param Comment $comment
     * @return Response
     */
    public function read(Comment $comment): Response{
        return $this->render('admin/comment/read.html.twig', [
            'comment' => $comment
        ]);
    }


    /**
     * @Route ("/{id}/delete", name="delete")
     * @param Comment $comment
     * @param EntityManagerInterface $em
     * @return RedirectResponse
     */
    public function delete(Comment $comment, EntityManagerInterface $em): RedirectResponse    {
            $em->remove($comment);
            $em->flush();
            $this->addFlash('success', 'Le commentaire a été supprimé');

        return $this->redirectToRoute('app_admin_comment_index');
    }
}