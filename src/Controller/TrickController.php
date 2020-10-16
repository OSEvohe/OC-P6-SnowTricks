<?php


namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\TrickType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
     * @param Request $request
     * @param SluggerInterface $slugger
     * @return Response
     */
    public function add(Request $request, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(TrickType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Trick $trick */
            $trick = $form->getData();
            $cover = $form->get('cover')->getData();

            $trick->getCover()->setContent('figure1');
            $trick->setUser($this->getUser())
                ->setCover($trick->getCover());
            $trick->getCover()->setTrick($trick);
            $em = $this->getDoctrine()->getManager();
            $em->persist($trick);
            $em->flush();


            //$this->addFlash('success', 'Le Trick a été crée');
            //return $this->redirectToRoute('trick_detail', ['slug' => $trick->getSlug()]);
        }

        return $this->render('trick/add.html.twig', [
            'form' => $form->createView()
        ]);
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

    private function checkTrickExists($trick){
        if (!$trick) {
            throw $this->createNotFoundException('Cette figure n\'existe pas');
        }
    }
}