<?php

namespace App\Controller\Api;

use App\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Usuario;
use App\Entity\Localidad;
use App\Entity\Reserva;
use App\Entity\Tour;
use App\Service\FileUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

#[Route('/api/reserva')]
class ApiReserva extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    #[Route('/all', name: 'getReservas', methods: ['GET'])]
    public function getReservass(): Response
    {
        $reservas = $this->entityManager->getRepository(Reserva::class)->findAll();
        $reservasJson = json_encode($reservas);
        if ($reservasJson == null) {
            return new Response(null, 404, $headers = ["no se han encontrado reservas"]);
        }
        return new Response($reservasJson, 200, $headers = ["Content-Type" => "application/json"]);
    }

    #[Route('/pagina', name: 'getPaginaReservas', methods: ['GET'])]
    public function getPaginaReservas(): Response
    {
        $reservas = $this->entityManager->getRepository(Reserva::class)->findMejoresReservas();
        $reservasJson = json_encode($reservas);
        if ($reservasJson == null) {
            return new Response(null, 404, $headers = ["no se han encontrado reservas"]);
        }
        return new Response($reservasJson, 200, $headers = ["Content-Type" => "application/json"]);
    }

    #[Route('/localidad/{id}', name: 'getReservasByLocalidad', methods: ['GET'])]
    public function getReservasByLocalidad(Localidad $localidad): JsonResponse
    {
        $items = $this->entityManager->getRepository(Item::class)->findBy(['localidad' => $localidad]);

        $reservas = [];
        foreach ($items as $item) {
            // obtiene las reservas de cada item
            $reservasItem = $this->entityManager->getRepository(Reserva::class)->findBy(['item' => $item]);
            // Añade las Reservas al array de reservas en bruto
            $reservas = array_merge($reservas, $reservasItem);
        }
        // Elimina las reservas duplicadas
        $reservas = array_unique($reservas);
        $reservaJson=[];
        foreach ($reservas as $reserva) {
            $reservaJson[] = $reserva->jsonSerialize();
        }

        if ($reservaJson == []) {
            return new Response(null, 404, $headers = ["no se han encontrado reservas"]);
        }
        return new JsonResponse($reservaJson, 200, $headers = ["Content-Type" => "application/json"]);
    }

    #[Route('/tour/{id}', name: 'reservasPorTour', methods: 'GET')]
    public function reservasPorTour($id): Response
    {
        $tour = $this->entityManager->getRepository(Tour::class)->find($id);
        $reservas = $this->entityManager->getRepository(Reserva::class)->findBy(['tour' => $tour]);
        $reservasJson=[];
        foreach ($reservas as $reserva) {
            $reservasJson[]=$reserva->jsonSerialize();
        }
        if ($reservasJson == []) {
            return new Response(null, 404, $headers = ["no se han encontrado reservas"]);
        }
        return new JsonResponse($reservasJson, 200, $headers = ["Content-Type" => "application/json"]);
    }

    #[Route('/guardaReserva', name: 'guardaReserva', methods: ['POST'])]
    public function creaReserva(HttpFoundationRequest $request): Response
    {
        try {
            $data = $request->getContent();
            // var_dump($request->request->all());

            // if ($data) {
                $foto = $request->files->get('foto');
                $nombre = $request->request->get('nombre');
                $coordInicio = $request->request->get('coordInicio');
                $descripcion = $request->request->get('descripcion');
                $inicio = $request->request->get('inicio');
                $fin = $request->request->get('fin');
                $aforo = $request->request->get('aforo');
                $programacion = json_decode($request->request->get('programacion'), true);
                $items = json_decode($request->request->get('items'), true);

                //Creación de Reserva nueva
                $reserva = new Reserva();

                $this->entityManager->persist($reserva);
                $this->entityManager->flush();
                return new JsonResponse($reserva->jsonSerialize(), 201, $headers = ["Content-Type" => "application/json"]);
            // } else{
            //     throw new \Exception('No se han recibido datos');
            // }

        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }

    }

    #[Route('/cancelaReserva', name: 'cancelaReserva', methods: ['DELETE'])]
    public function cancelaReserva(HttpFoundationRequest $request): Response
    {
        try {
            $idReserva = $request->request->get('idReserva');
            $reserva = $this->entityManager->getRepository(Reserva::class)->find($idReserva);
    
            if (!$reserva) {
                throw new \Exception('Reserva no encontrada');
            }
    
            $this->entityManager->remove($reserva);
            $this->entityManager->flush();
    
            return new JsonResponse(["success" => "reserva borrada"], 200, $headers = ["Content-Type" => "application/json"]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }

    }


}