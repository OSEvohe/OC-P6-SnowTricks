<?php


namespace App\Controller;


use App\Entity\Trick;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends AbstractController
{
    /**
     * View Homepage
     *
     * @Route ("/", name="home")
     * @param TrickRepository $em
     * @return Response
     */
    public function index(TrickRepository $em): Response
    {
        $tricks = $em->findBy([], ['updatedAt' => 'DESC'],12);
        return $this->render('index.html.twig', [
            'tricks' => $tricks,
            'countTotal' => $em->count([])
        ]);
    }


    /**
     * Fetch more trick in JSON response
     *
     * @Route ("/trick/load-more/{offset}/{limit}", name="load_more_tricks")
     * @param TrickRepository $em
     * @param SerializerInterface $serializer
     * @param int $offset
     * @param int $limit
     * @return Response
     */
    public function loadMore(TrickRepository $em, SerializerInterface $serializer, int $offset = 0, int $limit = 15): Response
    {
        $tricks = $em->findBy([], ['updatedAt' => 'DESC'], $limit, $offset);

        // Return an array with the slug, trick detail route and trick delete route
        $slugCallback = function ($value, $object, string $attributeName, string $format = null, array $context = [])
        {
           return [
               'slug' => $value,
               'detail_route' => $this->generateUrl('trick_detail', ['slug' => $value]),
               'edit_route' => $this->generateUrl('trick_edit', ['slug' => $value]),
               'delete_route' => $this->generateUrl('trick_delete', ['slug' => $value]),
               ];
        };

        return new JsonResponse(
            $serializer->serialize($tricks, 'json', [
                AbstractNormalizer::ATTRIBUTES => [
                    'name',
                    'slug',
                    'cover' => ['content'],
                ],
                AbstractNormalizer::CALLBACKS => [
                    'slug' => $slugCallback,
                ],
            ])
            , JsonResponse::HTTP_OK, [], true
        );
    }
}