<?php

namespace App\Controller\Admin;

use App\Entity\Usuario;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use phpDocumentor\Reflection\Types\Boolean;
use EasyCorp\Bundle\EasyAdminBundle\Controller\CrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Component\Validator\Constraints\Choice;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class UsuarioCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Usuario::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        $roles = [
            'Administrador' => 'ROLE_ADMIN',
            'Guía' => 'ROLE_GUIA',
            'Usuario' => 'ROLE_USER',
        ];
        if (Crud::PAGE_INDEX===$pageName) { 
            return [
                yield IdField::new('id'),
                yield TextField::new('email'),
                // yield TextField::new('nombre'),
                // yield TextField::new('apellidos'),
                yield TextField::new('fullName')->setLabel('Nombre completo'),
                yield BooleanField::new('isVerified'),
                // yield ImageField::new('foto')
                //                     ->setBasePath('fotos/')
                //                     ->setUploadDir('public/fotos'),
                yield ChoiceField::new('roles')
                                    ->setChoices($roles),
            ];
        } elseif (Crud::PAGE_DETAIL===$pageName) {
            return [
                // IdField::new('id'),
                yield TextField::new('email'),
                yield TextField::new('nombre'),
                yield TextField::new('apellidos'),
                // yield TextField::new('fullName'),
                yield TextField::new('password'),
                yield BooleanField::new('isVerified'),
                yield ChoiceField::new('roles')
                                            ->setChoices($roles),
                yield ImageField::new('foto')
                                            ->setBasePath('fotos/')
                                            ->setUploadDir('public/fotos')
                                            ->setLabel('Foto de perfil'),
                // yield ArrayField::new('reservas'),
                // yield ArrayField::new('tours'),
            ];
        } elseif (Crud::PAGE_NEW===$pageName) {
            return [
                yield TextField::new('email'),
                yield TextField::new('nombre'),
                yield TextField::new('apellidos'),
                yield TextField::new('password'),
                yield BooleanField::new('isVerified'),
                yield ImageField::new('foto')
                                            ->setBasePath('fotos/')
                                            ->setUploadDir('public/fotos')
                                            ->setLabel('Foto de perfil'),
                yield ChoiceField::new('roles')
                                            ->setChoices($roles)
                                            ->allowMultipleChoices(),
            ];
        } elseif (Crud::PAGE_EDIT===$pageName) {
            return [
                yield TextField::new('email'),
                yield TextField::new('nombre'),
                yield TextField::new('apellidos'),
                yield BooleanField::new('isVerified'),
                yield ImageField::new('foto')
                                            ->setBasePath('fotos/')
                                            ->setUploadDir('public/fotos')
                                            ->setLabel('Foto de perfil'),
                yield ChoiceField::new('roles')
                                            ->setChoices($roles)
                                            ->allowMultipleChoices(),
            ];
        }
       
    }
    
}
