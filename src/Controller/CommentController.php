<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommentController
 * @package App\Controller
 */
class CommentController extends AbstractController
{

    /**
     * @Route ("/comments/load-more", name="load_more_comments", methods={"GET"})
     *
     * @return Response
     */
    public function loadMore(): Response
    {
        $response = new Response(json_encode(array())); /** TODO : array will be filled with comments data */
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route ("/comment/add", name="add_comment", methods={"POST", "GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        return $this->redirectToRoute("trick_detail", ['slug' => $request->request->get('slug')]);
    }
}