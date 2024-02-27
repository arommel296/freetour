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
use App\Entity\Tour;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/api/tour')]
class ApiTour extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    #[Route('/unico/{id}', name: 'getTour', methods: ['GET'])]
    public function getTour(Tour $tour): Response
    {
        $tr = $tour->getId();
        if($tr == null){
            return new Response(null, 404, $headers = ["no se ha encontrado el tour"]);
        }else{
            // $url = $this->generateUrl('localidad_json', ['id' => $localidad->getId()]);
            return new Response($tr, 200, $headers = ["Content-Type" => "application/json"]);
        }
    }

    #[Route('/all', name: 'getTours', methods: ['GET'])]
    public function getTours(): Response
    {
        $tours = $this->entityManager->getRepository(Tour::class)->findAll();

        $toursJson = [];
        foreach ($tours as $tour) {
            $toursJson[] = $tour->jsonSerialize();
        }

        // $toursJson = json_encode($tours);
        if ($toursJson == null) {
            return new Response(null, 404, $headers = ["no se han encontrado tours"]);
        }
        return new JsonResponse($toursJson, 200, $headers = ["Content-Type" => "application/json"]);
    }

    #[Route('/all/fechas/ruta/{id}', name: 'getFechasTours', methods: ['GET'])]
    public function getFechasTours($id): Response
    {
        $hoy = new \DateTime;
        $tours = $this->entityManager->getRepository(Tour::class)->findToursBiggerDate($hoy, $id);
        // $ruta = $this->entityManager->getRepository(Ruta::class)->find($id);
        // $tours = $ruta->getTours();
        // $tours = $this->entityManager->getRepository(Tour::class)->findAll();

        $toursJson = [];
        foreach ($tours as $tour) {
            $toursJson[] = json_encode($tour->getFechaHora());
        }

        // $toursJson = json_encode($tours);
        if ($toursJson == null) {
            return new Response(null, 404, $headers = ["no se han encontrado tours"]);
        }
        return new JsonResponse($toursJson, 200, $headers = ["Content-Type" => "application/json"]);
    }

    #[Route('/all/day/ruta/{id}/{fecha}', name: 'getToursOfDay', methods: ['GET'])]
    public function getToursOfDay($id, $fecha): Response
    {
        $fecha = \DateTime::createFromFormat('d-m-Y', $fecha);
        $tours = $this->entityManager->getRepository(Tour::class)->findToursDate($fecha, $id);

        $toursJson = [];
        foreach ($tours as $tour) {
            $tourJson['tour'] = $tour->jsonSerialize();
            $tourJson['aforo'] = $this->entityManager->getRepository(Tour::class)->findAvailableSeats($tour->getId());
            $toursJson[] = $tourJson;
        }

        if ($toursJson == null) {
            return new Response(null, 404, $headers = ["no se han encontrado tours"]);
        }
        return new JsonResponse($toursJson, 200, $headers = ["Content-Type" => "application/json"]);
    }


    #[Route('/guia/{id}', name: 'getToursByGuia', methods: ['GET'])]
    public function getItemsByGuia(Usuario $guia): Response
    {
        $tours = $this->entityManager->getRepository(Tour::class)->findBy(['usuario' => $guia]);
        $toursJson=[];
        foreach ($tours as $tour) {
            $toursJson[] = $tour->jsonSerialize();
        }

        if ($toursJson == []) {
            return new JsonResponse(null, 404, $headers = ["no se han encontrado tours"]);
        }
        return new JsonResponse($toursJson, 200, $headers = ["Content-Type" => "application/json"]);
    }

    #[Route('/all/today', name: 'getToursToday', methods: ['GET'])]
    public function getToursToday(): Response
    {
        $tours = $this->entityManager->getRepository(Localidad::class)->findAll();
        $toursJson = json_encode($tours);
        if ($toursJson == null) {
            return new Response(null, 404, $headers = ["no se han encontrado tours"]);
        }
        return new Response($toursJson, 200, $headers = ["Content-Type" => "application/json"]);
    }

    #[Route('/all/guia', name: 'getAllToursGuia', methods: ['GET'])]
    public function getAllToursGuia(): Response
    {
        $guia = $this->getUser();
        $fecha = new \DateTime;
        $fecha->setTime(0, 0);

        $fechaFin = clone $fecha;
        $fechaFin->setTime(23, 59, 59);

        $tours = $this->entityManager->getRepository(Tour::class)->createQueryBuilder('tour')
            ->where('tour.usuario = :guia')
            ->setParameters([
                'guia' => $guia,
            ])
            ->getQuery()
            ->getResult();

        $toursJson=[];
        foreach ($tours as $tour) {
            $toursJson[]=$tour->jsonSerialize();
        }

        if ($toursJson == null) {
            return new Response(null, 404, $headers = ["no se han encontrado tours"]);
        }
        return new JsonResponse($toursJson, 200, $headers = ["Content-Type" => "application/json"]);
    }

    #[Route('/today/guia', name: 'getToursGuia', methods: ['GET'])]
    public function getToursGuia(): Response
    {
        $guia = $this->getUser();
        $fecha = new \DateTime;
        $fecha->setTime(0, 0);

        $fechaFin = clone $fecha;
        $fechaFin->setTime(23, 59, 59);

        $tours = $this->entityManager->getRepository(Tour::class)->createQueryBuilder('tour')
            ->where('tour.usuario = :guia')
            ->andWhere('tour.fechaHora BETWEEN :fechaInicio AND :fechaFin')
            ->setParameters([
                'guia' => $guia,
                'fechaInicio' => $fecha,
                'fechaFin' => $fechaFin,
            ])
            ->getQuery()
            ->getResult();

        $toursJson=[];
        foreach ($tours as $tour) {
            $toursJson[]=$tour->jsonSerialize();
        }

        if ($toursJson == null) {
            return new Response(null, 404, $headers = ["no se han encontrado tours"]);
        }
        return new JsonResponse($toursJson, 200, $headers = ["Content-Type" => "application/json"]);
    }

    
}