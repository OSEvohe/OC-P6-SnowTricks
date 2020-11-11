<?php


namespace App\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/_sntrks_admin", name="app_admin_")
 */
class AdminController extends AbstractController
{

    /**
     * @Route ("/", name="index")
     * @return Response
     */
    public function index(): Response{


        return $this->render('admin/index.html.twig');

    }

}