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

    #[Route('/guia/{id}', name: 'getToursByGuia', methods: ['GET'])]
    public function getItemsByGuia(Usuario $guia): Response
    {
        $tours = $this->entityManager->getRepository(Tour::class)->findBy(['guia' => $guia]);
        $toursJson=[];
        foreach ($tours as $tour) {
            $toursJson[] = $tour->jsonSerialize();
        }

        if ($toursJson == []) {
            return new Response(null, 404, $headers = ["no se han encontrado tours"]);
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
    
}