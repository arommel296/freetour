<?php

namespace App\Controller\Admin;

use App\Entity\Item;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class ItemCrudController extends AbstractCrudController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getEntityFqcn(): string
    {
        return Item::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        // return parent::configureActions()
        return $actions
        // ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ->update(Crud::PAGE_INDEX, Action::NEW , function (Action $action) {
            return $action->linkToRoute('creaItem', []);
        })
        ->update(Crud::PAGE_INDEX, Action::EDIT , function (Action $action) {
            return $action->linkToCrudAction('editRedirect');
        });
    }

    #[Route('/creaItem', name: 'creaItem')]
    public function nuevoItem(): Response
    {
        return $this->render('item/nuevoItem.html.twig');
    }    
}
