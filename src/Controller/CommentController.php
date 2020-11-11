<?php


namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Repository\CommentRepository;
use DateTime;
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
     * @param CommentRepository $repository
     * @param SerializerInterface $serializer
     * @param int $offset
     * @param int $limit
     * @return JsonResponse
     */
    public function loadMore(Trick $trick, CommentRepository $repository, SerializerInterface $serializer, int $offset = 0, int $limit = 10): JsonResponse
    {
        $comments = $repository->findBy(['trick' => $trick], ['createdAt' => 'DESC'], $limit , $offset);

        $dateCallback = function ($value, $object, string $attributeName, string $format = null, array $context = [])
        {
            return $value instanceof DateTime ? $value->format('d/m/Y à H:i') : '';
        };

        return new JsonResponse(
            $serializer->serialize($comments, 'json', [
                AbstractNormalizer::ATTRIBUTES => [
                    'content',
                    'updatedAt',
                    'createdAt',
                    'user' => ['displayName', 'photo']
                ],
                AbstractNormalizer::CALLBACKS => [
                    'updatedAt' => $dateCallback,
                    'createdAt' => $dateCallback
                ]
            ])
            , JsonResponse::HTTP_OK, [], true
        );

    }
}