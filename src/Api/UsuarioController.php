<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Usuario;

class UsuarioController extends AbstractController
{
    #[Route('/api/usuario/{id}', name: 'usuario_show', methods: ['GET'])]
    public function getUsuario(Usuario $usuario): Response
    {
        return new JsonResponse($usuario);
    }




    public function index(): Response
    {
        return $this->render('usuario/index.html.twig', [
            'controller_name' => 'UsuarioController',
        ]);
    }
}
