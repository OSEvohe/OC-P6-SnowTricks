<?php


namespace App\Controller\Admin;


use App\Entity\TrickGroup;
use App\Form\Admin\TrickGroupType;
use App\Repository\TrickGroupRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/_sntrks_admin/trick-group", name="app_admin_trickgroup_")
 */
class TrickGroupController extends AbstractController
{

    /**
     * @Route ("/", name="index")
     * @param TrickGroupRepository $repository
     * @return Response
     */
    public function index(TrickGroupRepository $repository): Response
    {

        return $this->render('admin/trickgroup/index.html.twig', [
            'trickgroups' => $repository->findAll()
        ]);
    }

    /**
     * @Route ("/{slug}/edit", name="update")
     * @param Request $request
     * @param TrickGroup $trickGroup
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function update(Request $request, TrickGroup $trickGroup, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TrickGroupType::class, $trickGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           $em->persist($form->getData());
           $em->flush();

            $this->addFlash('success', 'Le groupe a été modifié');
            return $this->redirectToRoute('app_admin_trickgroup_index');
        }

        return $this->render('admin/trickgroup/update.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route ("/{slug}/delete", name="delete")
     * @param TrickGroup $trickGroup
     * @param EntityManagerInterface $em
     * @param TrickGroupRepository $repository
     * @return RedirectResponse
     */
    public function delete(TrickGroup $trickGroup, EntityManagerInterface $em, TrickGroupRepository $repository): RedirectResponse
    {
        if (count($trickGroup->getTricks())){
            $this->addFlash('error', 'Un groupe contenant des tricks ne peut être supprimé');
        } else {
            $em->remove($trickGroup);
            $em->flush();
            $this->addFlash('success', 'Le groupe a été supprimé');
        }

        return $this->redirectToRoute('app_admin_trickgroup_index');
    }

    /**
     * @Route ("/create", name="create")
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Response
     */
    public function create(EntityManagerInterface $em, Request $request): Response
    {
        $trickgroup = new TrickGroup();

        $form = $this->createForm(TrickGroupType::class, $trickgroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($form->getData());
            $em->flush();

            $this->addFlash('success', 'Le groupe a été crée');
            return $this->redirectToRoute('app_admin_trickgroup_index');
        }

        return $this->render('admin/trickgroup/create.html.twig', [
            'form' => $form->createView()
        ]);


    }
}