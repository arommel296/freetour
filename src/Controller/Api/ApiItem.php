<?php

namespace App\Controller\Api;

use App\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Usuario;
use App\Entity\Localidad;
use App\Entity\Ruta;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/api/item')]
class ApiItem extends AbstractController
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

    #[Route('/{id}', name: 'getItem', methods: ['GET'])]
    public function getItem(Item $item): Response
    {
        $it = $item->getId();
        if($it == null){
            return new Response(null, 404, $headers = ["no se ha encontrado el item"]);
        }else{
            // $url = $this->generateUrl('localidad_json', ['id' => $localidad->getId()]);
            return new Response($it, 200, $headers = ["Content-Type" => "application/json"]);
        }
    }

    #[Route('/all', name: 'getItems', methods: ['GET'])]
    public function getItems(): Response
    {
        $items = $this->entityManager->getRepository(Localidad::class)->findAll();
        $itemsJson = json_encode($items);
        if ($itemsJson == null) {
            return new Response(null, 404, $headers = ["no se han encontrado items"]);
        }
        return new Response($itemsJson, 200, $headers = ["Content-Type" => "application/json"]);
    }

    #[Route('/localidad/{id}', name: 'getItemsByLocalidad', methods: ['GET'])]
    public function getItemsByLocalidad(Localidad $localidad): Response
    {
        $items = $this->entityManager->getRepository(Item::class)->findBy(['localidad' => $localidad]);
        $itemJson=[];
        foreach ($items as $item) {
            $itemJson[] = $item->jsonSerialize();
        }

        if ($itemJson == []) {
            return new Response(null, 404, $headers = ["no se han encontrado items"]);
        }
        return new JsonResponse($itemJson, 200, $headers = ["Content-Type" => "application/json"]);
    }

    #[Route('/ruta/{id}', name: 'getItemsByRuta', methods: ['GET'])]
    public function getItemsByRuta(Ruta $ruta): Response
    {
        $items = $this->entityManager->getRepository(Item::class)->findBy(['items' => $ruta->getItems()]);
        $itemJson=[];
        foreach ($items as $item) {
            $itemJson[] = $item->jsonSerialize();
        }

        if ($itemJson == []) {
            return new Response(null, 404, $headers = ["no se han encontrado items para esta ruta"]);
        }
        return new JsonResponse($itemJson, 200, $headers = ["Content-Type" => "application/json"]);
    }

    #[Route('/all/ruta/{id}', name: 'getItemsWithoutRuta', methods: ['GET'])]
    public function getItemsWithoutRuta(Ruta $ruta): Response
    {
        $items = $this->entityManager->getRepository(Item::class)->findAll();
        $itemJson=[];
        foreach ($items as $item) {
            if (!in_array($item, $ruta->getItems()->toArray())) { // Si el item no está en la ruta
                $itemJson[] = $item->jsonSerialize(); // Lo añadimos al array
            }
        }

        if ($itemJson == []) {
            return new Response(null, 404, $headers = ["no se han encontrado items"]);
        }
        return new JsonResponse($itemJson, 200, $headers = ["Content-Type" => "application/json"]);
    }

    public function index(): Response
    {
        return $this->render('item/index.html.twig', [
            'controller_name' => 'ItemController',
        ]);
    }


}