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

#[Route('/api/ruta')]
class ApiRuta extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    #[Route('/all', name: 'getRutas', methods: ['GET'])]
    public function getRutas(): Response
    {
        $rutas = $this->entityManager->getRepository(Localidad::class)->findAll();
        $rutasJson = json_encode($rutas);
        if ($rutasJson == null) {
            return new Response(null, 404, $headers = ["no se han encontrado Rutas"]);
        }
        return new Response($rutasJson, 200, $headers = ["Content-Type" => "application/json"]);
    }

    #[Route('/localidad/{id}', name: 'getRutasByLocalidad', methods: ['GET'])]
    public function getRutasByLocalidad(Localidad $localidad): Response
    {
        $items = $this->entityManager->getRepository(Item::class)->findBy(['localidad' => $localidad]);

        $rutas = [];
        foreach ($items as $item) {
            // obtiene las Rutas de cada item
            $rutasItem = $this->entityManager->getRepository(Ruta::class)->findBy(['item' => $item]);
            // Añade las Rutas al array de rutas en bruto
            $rutas = array_merge($rutas, $rutasItem);
        }
        // Elimina las Rutas duplicadas
        $rutas = array_unique($rutas);
        $rutaJson=[];
        foreach ($rutas as $ruta) {
            $rutaJson[] = $ruta->jsonSerialize();
        }

        if ($rutaJson == []) {
            return new Response(null, 404, $headers = ["no se han encontrado rutas"]);
        }
        return new JsonResponse($rutaJson, 200, $headers = ["Content-Type" => "application/json"]);
    }

    #[Route('/guardaRuta', name: 'guardaRuta', methods: ['POST'])]
    public function creaRuta(): Response
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $ruta = new Ruta();
            $ruta->setNombre($data['nombre']);
            $ruta->setCoordInicio($data['coordInicio']);
            $ruta->setDescripcion($data['descripcion']);
            $ruta->setFoto($data['foto']);
            $ruta->setInicio(new \DateTime($data['inicio']));
            $ruta->setFin(new \DateTime($data['fin']));
            $ruta->setAforo($data['aforo']);
            $ruta->setProgramacion($data['programacion']);
            // Añade cada item a la ruta
            foreach ($data['items'] as $itemId) {
                $item = $this->entityManager->getRepository(Item::class)->find($itemId);
                $ruta->addItem($item);
            }
            $this->entityManager->persist($ruta);
            $this->entityManager->flush();
            return new JsonResponse($ruta->jsonSerialize(), 201, $headers = ["Content-Type" => "application/json"]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
        
    }
}