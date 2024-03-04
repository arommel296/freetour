<?php

namespace App\Controller;

use App\Entity\Reserva;
use App\Entity\Ruta;
use App\Entity\Tour;
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

        if (!$reserva) {
            throw $this->createNotFoundException('No se encontró la reserva con id '.$id);
        }

        // Buscar una valoración existente para la reserva
        $valoracion = $this->entityManager->getRepository(Valoracion::class)->findOneBy(['reserva' => $reserva]);

        // Si no existe una valoración, crear una nueva
        if (!$valoracion) {
            $valoracion = new Valoracion();
            $valoracion->setReserva($reserva);
        }

        $form = $this->createForm(ValoracionType::class, $valoracion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($valoracion);
            $this->entityManager->flush();

            return $this->redirectToRoute('principal');
        }

        return $this->render('valoracion/valoracion.html.twig', [
            'reserva' => $reserva,
            'form' => $form->createView(),
        ]);
    }

    // #[Route('/valorar/reserva/{id}/formulario', name: 'valorar_formulario')]
    // public function valorarFormulario($id): Response
    // {
    //     $reserva = $this->entityManager->getRepository(Reserva::class)->find($id);

    //     if (!$reserva) {
    //         throw $this->createNotFoundException('No se encontró la reserva con id '.$id);
    //     }

    //     $valoracion = new Valoracion();
    //     $valoracion->setReserva($reserva);

    //     $form = $this->createForm(ValoracionType::class, $valoracion);

    //     return $this->render('valoracion/valoracion.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

    // #[Route('/valorar/reserva/{id}', name: 'valorar')]
    // public function valorar($id, Request $request): Response
    // {
    //     $reserva = $this->entityManager->getRepository(Reserva::class)->find($id);

    //     if (!$reserva) {
    //         throw $this->createNotFoundException('No se encontró la reserva con id '.$id);
    //     }

    //     $valoracion = new Valoracion();
    //     $valoracion->setReserva($reserva);

    //     $form = $this->createForm(ValoracionType::class, $valoracion);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $this->entityManager->persist($valoracion);
    //         $this->entityManager->flush();

    //         return $this->redirectToRoute('principal');
    //     }

    //     // Si el formulario no es válido o no se ha enviado, redirige de nuevo al formulario
    //     return $this->redirectToRoute('valorar_formulario', ['id' => $id]);
    // }

}
