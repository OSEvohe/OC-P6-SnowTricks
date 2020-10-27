<?php


namespace App\Controller;


use App\Entity\Trick;
use App\Service\ManageTrick;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{
    /**
     * @Route ("trick/{slug}/cover/delete", name="cover_delete", methods={"GET"})
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

        return $this->redirectToRoute(TrickController::REDIRECT_POST_EDIT, ['slug' => $trick->getSlug()]);
    }
}