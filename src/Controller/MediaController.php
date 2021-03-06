<?php


namespace App\Controller;


use App\Entity\Trick;
use App\Entity\TrickMedia;
use App\Service\ImageUploader;
use App\Service\ManageTrick;
use App\Service\YoutubeHelper;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{
    /**
     * Remove a media as a cover, media is not deleted
     * @Route ("trick/{slug}/cover/delete", name="cover_delete", methods={"GET"})
     * @IsGranted("ROLE_USER_VERIFIED")
     * @param Trick $trick
     * @param ManageTrick $manageTrick
     * @return Response
     */
    public function deleteCover(Trick $trick, ManageTrick $manageTrick): Response
    {
        if (!$trick) {
            throw $this->createNotFoundException('Cette figure n\'existe pas');
        }

        $trick->setCover(null);
        $manageTrick->update($trick);

        $this->addFlash('success', 'Image principale supprimée');
        return $this->redirectToRoute(TrickController::REDIRECT_POST_EDIT, ['slug' => $trick->getSlug()]);
    }

    /**
     * Delete a media
     * @Route ("/media/{id}/delete", name="media_delete", methods={"GET"})
     * @IsGranted("ROLE_USER_VERIFIED")
     * @param TrickMedia $media
     * @param ImageUploader $imageUploader
     * @return Response
     */
    public function delete(TrickMedia $media, ImageUploader $imageUploader): Response
    {
        if (!$media) {
            throw $this->createNotFoundException('Ce media n\'existe pas');
        }

        if ($media->getType() == TrickMedia::MEDIA_TYPE_IMAGE){
            $imageUploader->deleteFile($media->getContent());
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($media);
        $entityManager->flush();

        $this->addFlash('success', 'Media supprimé');
        return $this->redirectToRoute(TrickController::REDIRECT_POST_EDIT, ['slug' => $media->getTrick()->getSlug()]);
    }


    /**
     * Return Youtube video information as json response
     * @Route("/media/video-info-{id}", name="media_video_info")
     *
     * @param string $id
     * @param YoutubeHelper $youtubeHelper
     * @return JsonResponse
     */
    public function youtubeVideoInfo(string $id, YoutubeHelper $youtubeHelper): JsonResponse{
        return new JsonResponse($youtubeHelper->getVideoInfo($youtubeHelper->getUrlFromId($id)), JsonResponse::HTTP_OK,[], false);
    }
}