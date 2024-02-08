<?php

namespace App\Controller\Admin;

use App\Entity\Ruta;
use App\Entity\Item;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

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
        // return [
        //     TextField::new('nombre'),
        //     TextEditorField::new('descripcion')
        //         ->setFormTypeOptions([
        //             'attr' => ['maxlength' => 1000]
        //         ]),
        //     DateField::new('inicio'),
        //     DateField::new('fin'),
        //     IntegerField::new('aforo'),
        //     ChoiceField::new('coord_inicio')->setChoices(function () {
        //         $listaItems = [];
        //         $items = $this->entityManager->getRepository(Item::class)->findAll();
        //         foreach ($items as $item) {
        //             $listaItems[$item->getNombre()] = $item->getId();
        //         }
        //         return $listaItems;
        //     }),
        //     ImageField::new('foto')
        //         ->setBasePath('fotos/')
        //         ->setUploadDir('public/fotos')
        //         ->setUploadedFileNamePattern('[randomhash].[extension]'),
        // ];
        return [];
    }

    public function configureActions(Actions $actions): Actions
    {
        // return parent::configureActions()
        return $actions
        // ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ->update(Crud::PAGE_INDEX, Action::NEW , function (Action $action) {
            return $action->linkToRoute('creaRuta', []);
        })
        ->update(Crud::PAGE_INDEX, Action::EDIT , function (Action $action) {
            return $action->linkToCrudAction('editRedirect');
        });
    }

    #[Route('/creaRuta', name: 'creaRuta')]
    public function nuevaRuta(): Response
    {
        return $this->render('ruta/nuevaRuta.html.twig');
    }    
}    


