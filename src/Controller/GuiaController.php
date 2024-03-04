<?php

namespace App\Controller;

use App\Entity\Tour;
use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
class GuiaController extends AbstractController
{
    public static function getEntityFqcn(): string
    {
        return Usuario::class;
    }

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route(path: '/calendarioGuia', name: 'calendarioGuia')]
    public function calendarioGuia(): Response
    {
        // echo 'El usuario actual es: ' . get_current_user();
        return $this->render('guia/calendarioGuia.html.twig');
    }

    #[Route(path: '/informes', name: 'informes')]
    public function informes(): Response
    {
        $usuario = $this->getUser();

        $start = new \DateTime('today');
        $end = (clone $start)->modify('+1 day');

        $tours = $this->entityManager->getRepository(Tour::class)->findToursByUser($usuario, $start, $end);

        $tours = array_filter($tours, function($tour) {
            return count($tour->getReservas()) > 0;
        });

        // echo 'El usuario actual es: ' . get_current_user();
        return $this->render('guia/toursGuia.html.twig', [
            'tours' => $tours,
            // 'form' => $form->createView(),
        ]);
    }

}