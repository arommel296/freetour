<?php

namespace App\Controller\Admin;

use App\Entity\Tour;
use App\Entity\Ruta;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class TourCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tour::class;
    }

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function configureFields(string $pageName): iterable
    {
        // $rutas = [];
        $rutas = $this->entityManager->getRepository(Ruta::class)->findAll();
        return [
            yield ChoiceField::new('Ruta')->setChoices($rutas),
            yield DateTimeField::new('fecha_hora'),
            yield BooleanField::new('disponible'),
        ];
    }
}