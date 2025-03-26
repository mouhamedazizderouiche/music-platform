<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(): Response
    {
        // Renvoie la page de login statique
        return $this->render('login/login.html.twig');
    }
    #[Route('/create_account', name: 'app_create_account')]

    public function login(): Response
    {
        return $this->render('login/create_account.html.twig');
    }
}