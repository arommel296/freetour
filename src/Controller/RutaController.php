<?php

namespace App\Controller;

use App\Entity\Reserva;
use App\Entity\Ruta;
use App\Entity\Item;
use App\Form\ReservaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Component\HttpFoundation\Request;

class RutaController extends AbstractController
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

    #[Route('/verRuta/{id}', name: 'verRuta')]
    public function verRuta($id, Request $request): Response
    {

        $ruta = $this->entityManager->getRepository(Ruta::class)->find($id);
        $tours = $ruta->getTours();
        // var_dump($tours);
        $reserva = new Reserva();
        // $reserva->setFechaReserva(new \DateTime);
        $form = $this->createForm(ReservaType::class);
        $form->handleRequest($request);
        return $this->render('ruta/verRuta.html.twig', [
            'ruta' => $ruta,
            'tours' => $tours,
            'form' => $form->createView(),
        ]);
    }
}