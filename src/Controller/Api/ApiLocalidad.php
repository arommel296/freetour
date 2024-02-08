<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Usuario;
use App\Entity\Localidad;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/api/localidad')]
class ApiLocalidad extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
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

    // #[Route('/{id}', name: 'getLocalidad', methods: ['GET'])]
    // public function getLocalidad(Localidad $localidad): Response
    // {
    //     $loc = $localidad->getId();
    //     if($loc == null){
    //         return new Response(null, 404, $headers = ["no se ha encontrado la localidad"]);
    //     }else{
    //         // $url = $this->generateUrl('localidad_json', ['id' => $localidad->getId()]);
    //         return new Response($loc, 200, $headers = ["Content-Type" => "application/json"]);
    //     }
    // }

    #[Route('/all', name: 'findLocalidades', methods: ['GET'])]
    public function findLocalidades(): Response
    {
        $localidades = $this->entityManager->getRepository(Localidad::class)->findAll();
        $loc=[];
        foreach ($localidades as $localidad) {
            $loc[] = $localidad->jsonSerialize();
        }

        if ($loc == "") {
            return new JsonResponse(null, 404, $headers = ["no se han encontrado localidades"]);
        }
        return new JsonResponse($loc, 200, $headers = ["Content-Type" => "application/json"]);
    }

}