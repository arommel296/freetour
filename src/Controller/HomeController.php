<?php

namespace App\Controller;

use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
class HomeController extends AbstractController
{
    #[Route(path: '/home', name: 'principal')]
    public function home(): Response
    {
        // echo 'El usuario actual es: ' . get_current_user();
        return $this->render('home/home.html.twig');
    }

}