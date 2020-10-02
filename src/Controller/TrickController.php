<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    /**
     * View single trick
     *
     * @Route ("/trick/{slug}" , name="trick_detail")
     * @param string $slug
     * @return Response
     */
    public function view(string $slug): Response
    {
        return $this->render('trick/view.html.twig');
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