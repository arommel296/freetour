<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Usuario;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/api')]
class ApiLocalidad extends AbstractController
{
    // #[Route('/localidad/{id}', name: 'getLocalidad', methods: ['GET'])]
    // public function getLocalidad(Usuario $usuario): RedirectResponse
    // {
    //     $usu = $usuario->getId();
    //     if($usu == null){
    //         $this->addFlash('error', 'El usuario no existe');
    //         return new RedirectResponse('bundle/error404.html.tig', 404);
    //     }else{
    //         $url = $this->generateUrl('localidad_json', ['id' => $usuario->getId()]);
    //         return new RedirectResponse($url, 302);
    //     }
    // }

    // #[Route('/localidad/{id}/json', name: 'localidad_json', methods: ['GET'])]
    // public function getLocalidadJson(Usuario $usuario): Response
    // {
    //     return new JsonResponse($usuario);
    // }

    // #[Route('/api/localidades', name: 'getLocalidades', methods: ['GET'])]
    // public function getLocalidades(Usuario $usuarios): Response
    // {
    //     return $this->json($usuarios);
    // }
}