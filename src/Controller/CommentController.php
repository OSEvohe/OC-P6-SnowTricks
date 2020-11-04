<?php


namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class CommentController
 * @package App\Controller
 */
class CommentController extends AbstractController
{

    /**
     * @Route ("/comments/{slug}/load-more/{offset}/{limit}", name="load_more_comments", methods={"GET"})
     *
     * @param Trick $trick
     * @param CommentRepository $em
     * @param SerializerInterface $serializer
     * @param int $offset
     * @param int $limit
     * @return Response
     */
    public function loadMore(Trick $trick, CommentRepository $em, SerializerInterface $serializer, int $offset, int $limit): Response
    {
        $comments = $em->findBy(['trick' => $trick], ['createdAt' => 'DESC'], $limit , $offset);

        return new JsonResponse(
            $serializer->serialize($comments, 'json', [
                AbstractNormalizer::ATTRIBUTES => [
                    'content',
                    'updatedAt',
                    'createdAt',
                    'user' => ['displayName', 'photo']
                ]
            ])
            , JsonResponse::HTTP_OK, [], true
        );

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