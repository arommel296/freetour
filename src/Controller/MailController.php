<?php
// src/Controller/SiteController.php
namespace App\Controller;

use App\Service\CorreoManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MailController extends AbstractController
{
    #[Route('/notificacion', name: 'notifica_usuario',)]
    public function new(CorreoManager $correoManager): Response
    {

        if ($correoManager->notifyOfSiteUpdate()) {
            $this->addFlash('success', 'Enviado correctamente.');
            return new Response("correcto");
        }

        return new Response("incorrecto");
    }
}