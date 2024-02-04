<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Usuario;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/api')]
class UsuarioController extends AbstractController
{
    // #[Route('/api/usuario/{id}', name: 'getUsuario', methods: ['GET'])]
    // public function getUsuario(Usuario $usuario): Response
    // {
    //     return new JsonResponse($usuario);
    // }

    #[Route('/usuario/{id}', name: 'getUsuario', methods: ['GET'])]
    public function getUsuario(Usuario $usuario): RedirectResponse
    {
    // Obtiene la URL de la ruta que devuelve el usuario como JSON
        $usu = $usuario->getId();
        if($usu == null){
            $this->addFlash('error', 'El usuario no existe');
            return new RedirectResponse('bundle/error404.html.tig', 404);
        }else{
            $url = $this->generateUrl('usuario_json', ['id' => $usuario->getId()]);
            return new RedirectResponse($url, 302);
        }
    //quiero comprobar si el usuario existe y si no manejar el error
        // $url = $this->generateUrl('usuario_json', ['id' => $usuario->getId()]);

    // Devuelve la URL como respuesta
        
    }

    #[Route('/usuario/{id}/json', name: 'usuario_json', methods: ['GET'])]
    public function getUsuarioJson(Usuario $usuario): Response
    {
    // Devuelve el usuario como JSON
            return new JsonResponse($usuario);
    }

    #[Route('/api/usuarios', name: 'getUsuarios', methods: ['GET'])]
    public function getUsuarios(Usuario $usuarios): Response
    {
        return $this->json($usuarios);
    }


    public function index(): Response
    {
        return $this->render('usuario/index.html.twig', [
            'controller_name' => 'UsuarioController',
        ]);
    }
}
