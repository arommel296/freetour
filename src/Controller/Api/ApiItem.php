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
use App\Service\FileUploaderService;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

#[Route('/api/item')]
class ApiItem extends AbstractController
{
    private $entityManager;
    private $fileUploaderService;
    public function __construct(EntityManagerInterface $entityManager, FileUploaderService $fileUploaderService) {
        $this->entityManager = $entityManager;
        $this->fileUploaderService = $fileUploaderService;
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
        if ($this->estaLog('ROLE_ADMIN')) {
            $items = $this->entityManager->getRepository(Item::class)->findBy(['localidad' => $localidad]);
            $itemJson=[];
            foreach ($items as $item) {
                $itemJson[] = $item->jsonSerialize();
            }

            if ($itemJson == []) {
                return new Response(null, 404, $headers = ["no se han encontrado items"]);
            }
            return new JsonResponse($itemJson, 200, $headers = ["Content-Type" => "application/json"]);
        } else{
            return new JsonResponse(["error"=>"debes estar logueado"], 401, $headers = ["debes estar logueado"]);
        }
        
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

    /**
     * @IsGranted("ROLE_ADMIN")
     */
    #[Route('/guardaItem', name: 'guardaItem', methods: ['POST'])]
    public function guardaItem(HttpFoundationRequest $request): Response
    {
        if ($this->estaLog('ROLE_ADMIN')) {
            try {
                $foto = $request->files->get('foto');
                $fileName = $this->fileUploaderService->upload($foto);
                $nombre = $request->request->get('nombre');
                $coordenadas = $request->request->get('coordenadas');
                $descripcion = $request->request->get('descripcion');
                $local= $request->request->get('localidad');
                // var_dump($local);

                $item = new Item();
                // $localidad = new Localidad();
                $localidad = $this->entityManager->getRepository(Localidad::class)->find($local);
                $item->setCoordenadas($coordenadas);
                $item->setNombre($nombre);
                $item->setDescripcion($descripcion);
                $item->setFoto($fileName);
                $item->setLocalidad($localidad);

                $this->entityManager->persist($item);
                $this->entityManager->flush();
                return new JsonResponse($item->jsonSerialize(), 200, $headers = ["Content-Type" => "application/json"]);
                
            } catch (\Exception $e) {
                return new JsonResponse(['error' => $e->getMessage()], 400);
            }
        } else{
            return new JsonResponse(["error"=>"debes estar logueado"], 401, $headers = ["debes estar logueado"]);
        }
    }

    public function index(): Response
    {
        return $this->render('item/index.html.twig', [
            'controller_name' => 'ItemController',
        ]);
    }

    private function estaLog($rol):bool
    {
        if (in_array($rol,$this->getUser()->getRoles())) {
            return true;
        }
        return false;
    }


}