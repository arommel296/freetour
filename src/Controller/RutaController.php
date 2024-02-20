<?php

namespace App\Controller;

use App\Entity\Ruta;
use App\Entity\Item;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

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
    public function verRuta($id): Response
    {
        $ruta = $this->entityManager->getRepository(Ruta::class)->find($id);
        return $this->render('ruta/verRuta.html.twig', [
            'ruta' => $ruta,
        ]);
    }
}