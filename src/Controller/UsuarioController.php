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
        // TODO query the database
        //llamar a la base de datos y devuelve el usuario con el id que le pasamos
        // $user = $this->getDoctrine()
        //     ->getRepository(Usuario::class)
        //     ->find($id);
        // if (!$user) {
        //     throw $this->createNotFoundException(
        //         'No user found for id '.$id
        //     );
        // }
        // return new Response('Check out this great user: '.$user->getNombre());
        // return $this->render('usuario/index.html.twig', ['user' => $user]);
        
        // $user = [
        //     'id' => $id,
        //     'nombre' => 'nombre',
        //     'apellidos' => 'apellidos',
        //     'email' => 'email',
        //     'foto' => 'foto',
        //     'roles' => ['ROLE_USER'],
        // ];
        // $usuario->find($id);

        // $usuario = $this->getDoctrine()
        // ->getRepository(Usuario::class)
        // ->find($id);
        // if (!$usuario) {
        //     throw $this->createNotFoundException(
        //         'No hay usuario con id = '.$id
        //     );
        // }
        
        return new JsonResponse($usuario);
    }




    public function index(): Response
    {
        return $this->render('usuario/index.html.twig', [
            'controller_name' => 'UsuarioController',
        ]);
    }
}
