<?php

namespace App\Controller\Admin;

use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
class CalendarioController extends AbstractController
{
    #[Route(path: '/calendario', name: 'calendario')]
    public function calendario(): Response
    {
        // echo 'El usuario actual es: ' . get_current_user();
        return $this->render('admin/calendario.html.twig');
    }

}