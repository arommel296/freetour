<?php

namespace App\Controller;

use App\Entity\Reserva;
use App\Entity\Ruta;
use App\Entity\Valoracion;
use App\Form\ValoracionType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ValoracionController extends AbstractController
{

    public static function getEntityFqcn(): string
    {
        return Ruta::class;
    }

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/valorar/reserva/{id}', name: 'valorar')]
    public function valorar($id, Request $request): Response
    {
        $reserva = $this->entityManager->getRepository(Reserva::class)->find($id);

        $valoracion = new Valoracion();

        $form = $this->createForm(ValoracionType::class, $valoracion, [
            'reserva_id' => $id,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tourId = $request->get('tourId');
            $tour = $this->entityManager->getRepository(Tour::class)->find($tourId);
            $valoracion->setTour($tour);
            $valoracion->setUsuario($this->getUser());
            $nEntradas = $form->get('nEntradas')->getData();
            $valoracion->setNEntradas($nEntradas);
            $valoracion->setFechaReserva(new DateTime());

            $this->entityManager->persist($valoracion);
            $this->entityManager->flush();

            return $this->redirectToRoute('home');
        }
        return $this->render('valoracion/valoracion.html.twig', [
            'reserva' => $reserva,
            'form' => $form->createView(),
        ]);
    }

}
