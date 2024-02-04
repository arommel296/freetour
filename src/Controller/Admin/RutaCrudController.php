<?php

namespace App\Controller\Admin;

use App\Entity\Ruta;
use App\Entity\Item;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class RutaCrudController extends AbstractCrudController
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

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nombre'),
            TextEditorField::new('descripcion')
                ->setFormTypeOptions([
                    'attr' => ['maxlength' => 1000]
                ]),
            DateField::new('inicio'),
            DateField::new('fin'),
            IntegerField::new('aforo'),
            ChoiceField::new('coord_inicio')->setChoices(function () {
                $listaItems = [];
                $items = $this->entityManager->getRepository(Item::class)->findAll();
                foreach ($items as $item) {
                    $listaItems[$item->getNombre()] = $item->getId();
                }
                return $listaItems;
            }),
            ImageField::new('foto')
                ->setBasePath('fotos/')
                ->setUploadDir('public/fotos')
                ->setUploadedFileNamePattern('[randomhash].[extension]'),
        ];
    }

    #[Route('/creaRuta', name: 'creaRuta')]
    public function nuevaRuta(): Response
    {
        return $this->render('ruta/nuevaRuta.html.twig');
    }    
}    


