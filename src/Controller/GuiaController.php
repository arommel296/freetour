<?php

namespace App\Controller;

use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
class GuiaController extends AbstractController
{
    #[Route(path: '/calendarioGuia', name: 'calendarioGuia')]
    public function calendarioGuia(): Response
    {
        // echo 'El usuario actual es: ' . get_current_user();
        return $this->render('guia/calendarioGuia.html.twig');
    }

    #[Route(path: '/informes', name: 'informes')]
    public function informes(): Response
    {
        // echo 'El usuario actual es: ' . get_current_user();
        return $this->render('guia/toursGuia.html.twig');
    }

}