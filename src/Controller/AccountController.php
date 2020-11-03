<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class AccountController extends AbstractController
{

    /**
     * @Route ("/profile", name="user_profile")
     */
    public function profile(){


        return $this->render("users/profile.html.twig");
    }
}