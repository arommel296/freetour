<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/api/usuario')]
class UsuarioController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // #[Route('/{id}', name: 'getUsuario', methods: ['GET'])]
    // public function getUsuario(Usuario $usuario): Response
    // {
    //     return new JsonResponse($usuario);
    // }

    // #[Route('/{id}', name: 'getUsuario', methods: ['GET'])]
    // public function getUsuario(Usuario $usuario): RedirectResponse
    // {
    // // Obtiene la URL de la ruta que devuelve el usuario como JSON
    //     $usu = $usuario->getId();
    //     if($usu == null){
    //         $this->addFlash('error', 'El usuario no existe');
    //         return new RedirectResponse('error404.html.twig', 404);
    //     }else{
    //         $url = $this->generateUrl('usuario_json', ['id' => $usu]);
    //         return new RedirectResponse($url, 302);
    //     }

    // }

    // #[Route('/{id}/json', name: 'usuario_json', methods: ['GET'])]
    // public function getUsuarioJson(Usuario $usuario): JsonResponse
    // {
    // // Devuelve el usuario como JSON
    //         return new JsonResponse($usuario);
    // }

    #[Route('/guias', name: 'getGuias', methods: ['GET'])]
    public function getGuias(): JsonResponse
    {
        $usuarios = $this->entityManager->getRepository(Usuario::class)->findAll();
        
        $users = [];
        foreach($usuarios as $usuario){
            if (in_array("ROLE_GUIA", $usuario->getRoles())) {
                $users[] = $usuario->jsonSerialize();
            }
        }
        if ($users==[]) {
            return new JsonResponse(null, 404, $headers = ["no se han encontrado guias"]);
        }
        return new JsonResponse($users, 200, ["Content-Type" => "application/json"]);
    }

    #[Route('/all', name: 'getUsuarios', methods: ['GET'])]
    public function getUsuarios(): JsonResponse
    {
        $usuarios = $this->entityManager->getRepository(Usuario::class)->findAll();
        $users = [];
        foreach($usuarios as $usuario){
            $users[] = $usuario->jsonSerialize();
        }
        if ($users==[]) {
            return new JsonResponse(null, 404, $headers = ["no se han encontrado usuarios"]);
        }
        return new JsonResponse($users, 200, ["Content-Type" => "application/json"]);
    }

    public function index(): Response
    {
        return $this->render('usuario/index.html.twig', [
            'controller_name' => 'UsuarioController',
        ]);
    }
}

// use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Component\HttpFoundation\JsonResponse;
// use Symfony\Component\Routing\Annotation\Route;

// #[Route('/api/usuario')]
// class UsuarioController extends AbstractController
// {
//     private $entityManager;

//     public function __construct(EntityManagerInterface $entityManager)
//     {
//         $this->entityManager = $entityManager;
//     }

//     #[Route('/all', name: 'getUsuarios', methods: ['GET'])]
//     public function getUsuarios(): JsonResponse
//     {
//         $usuarios = $this->entityManager->getRepository(Usuario::class)->findAll();
//         $users = [];
//         foreach($usuarios as $usuario){
//             $users[] = $usuario->jsonSerialize();
//         }
//         return new JsonResponse($users, Response::HTTP_OK, ["Content-Type" => "application/json"]);
//     }
// }