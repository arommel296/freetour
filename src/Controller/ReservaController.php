<?php

namespace App\Controller;

use App\Entity\Reserva;
use App\Entity\Ruta;
use App\Entity\Item;
use App\Entity\Tour;
use App\Entity\Usuario;
use App\Entity\Valoracion;
use App\Form\ReservaType;
use App\Service\CorreoManager;
use App\Service\GeneraPdfService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ReservaController extends AbstractController
{
    public static function getEntityFqcn(): string
    {
        return Ruta::class;
    }

    private $entityManager;
    private $security;
    private $pdfService;
    private $correoManager;
    public function __construct(EntityManagerInterface $entityManager, Security $security, GeneraPdfService $pdfService, CorreoManager $correoManager)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->pdfService = $pdfService;
        $this->correoManager = $correoManager;
    }

    #[Route('/reservar/ruta/{id}', name: 'reservar')]
    public function reservar($id, Request $request): Response
    {
        $usuario = $this->getUser();
        $ruta = $this->entityManager->getRepository(Ruta::class)->find($id);
        $itemsRuta = $ruta->getItems();
        $reserva = new Reserva();
        // $valoracion = new Valoracion();
        $form = $this->createForm(ReservaType::class, $reserva, [
            'ruta_id' => $id,
            "label" => "Número de asistentes"
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tourId = $request->get('tourId');
            $tour = $this->entityManager->getRepository(Tour::class)->find($tourId);
            $reserva->setTour($tour);
            $reserva->setUsuario($this->getUser());
            $nEntradas = $form->get('nEntradas')->getData();
            $reserva->setNEntradas($nEntradas);
            $reserva->setFechaReserva(new DateTime());

            $this->entityManager->persist($reserva);
            $this->entityManager->flush();

            //Generación del html de la plantilla del pdf
            $plantilla = $this->renderView('pdf/reservaPdf.html.twig', [
                'tour' => $tour,
                'reserva' => $reserva,
                'usuario' => $usuario,
            ]);

            // Envío del correo electrónico con el pdf adjunto
            $correo = $usuario->getUserIdentifier();
            $subject = "Confirmación de reserva";
            $text = "Adjunto encontrarás la confirmación de tu reserva.";
            $errorPdf = $this->correoManager->sendEmailPdf($correo, $subject, $text, $plantilla);
            if (!$errorPdf) {
                $this->addFlash(
                    'error', 'hubo un problema al enviar su reserva por email, vuelva a intentarlo.'
                );
                return $this->render('reservas/reservar.html.twig', [
                    'ruta' => $ruta,
                    'items' => $itemsRuta,
                    'form' => $form->createView(),
                ]);
            }
            

            return $this->redirectToRoute('principal');
        }

        return $this->render('reservas/reservar.html.twig', [
            'ruta' => $ruta,
            'items' => $itemsRuta,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/listadoReservas', name: 'listadoReservas')]
    public function listadoReservas(): Response
    {

        $u = $this->security->getUser();
        // var_dump($u);
        $usuario = $this->entityManager->getRepository(Usuario::class)->find($u);
        // $reservas=$usuario->getReservas();
        // var_dump($u);

        

        // $reservas=[];
        $reservas = $this->entityManager->getRepository(Reserva::class)->findBy(['usuario' => $u]);
        $reservasJson=[];
        foreach ($reservas as $reserva) {
            $reservasJson[]=$reserva->jsonSerialize();
        }
        // try {
        //     // $reservas = $this->entityManager->getRepository(Reserva::class)->findBy(['usuario' => $u]);
        // } catch (\Throwable $th) {
        //     return new Response($th, 404, $headers = ["no se han encontrado tours"]);
        // }

        // return new JsonResponse($reservasJson, 200, $headers = ["no se han encontrado tours"]);

        return $this->render('reservas/listadoReservas.html.twig', [
            'reservas' => $reservas,
        ]);

        // return new JsonResponse($reservasJson, 201, $headers = ["no se han encontrado tours"]);

    }


}
