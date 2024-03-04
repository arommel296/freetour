<?php

namespace App\Controller\Api;

use App\Entity\Informe;
use App\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Usuario;
use App\Entity\Localidad;
use App\Entity\Reserva;
use App\Entity\Ruta;
use App\Entity\Tour;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Service\FileUploaderService;
use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

#[Route('/api/informeLista')]
class ApiInformeLista extends AbstractController
{
    private $entityManager;
    private $fileUploaderService;
    public function __construct(EntityManagerInterface $entityManager, FileUploaderService $fileUploaderService) {
        $this->entityManager = $entityManager;
        $this->fileUploaderService = $fileUploaderService;
    }

    #[Route('/guardar', name: 'guardaInformeLista', methods: ['POST'])]
    public function guardaInformeLista(Request $request): Response
    {
        $data = $request->request->all();
    
        // Crear un nuevo informe
        $informe = new Informe();
        $tour = $this->entityManager->getRepository(Tour::class)->find($data['tourId']);
        $informe->setTour($tour);
        $informe->setDinero($data['dinero']);
        $informe->setObservaciones($data['observaciones']);
        // Manejar el archivo de la foto
        $foto = $request->files->get('foto');
        if ($foto) {
            $filename = $this->fileUploaderService->upload($foto);
            $informe->setFoto($filename);
        }
    
        // Actualizar las reservas
        foreach ($data as $key => $value) {
            if (str_starts_with($key, 'reserva_')) {
                $reservaId = substr($key, 8);
                $reserva = $this->entityManager->getRepository(Reserva::class)->find($reservaId);
                if ($reserva) {
                    $reserva->setNAsistentes($value);
                }
            }
        }
    
        // Guardar el informe y las reservas en la base de datos
        $this->entityManager->persist($informe);
        $this->entityManager->flush();
    
        return new Response('Informe guardado con éxito', Response::HTTP_OK);
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