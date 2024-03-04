<?php

namespace App\Controller\Admin;

use App\Entity\Valoracion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ValoracionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Valoracion::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            NumberField::new('NotaGuia')
            ->setLabel("Nota del Guía"),
            NumberField::new('NotaRuta')
            ->setLabel("Nota de la Ruta"),
            TextField::new('comentario')
        ];
    }
    
}
