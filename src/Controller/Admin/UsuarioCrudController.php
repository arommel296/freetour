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
use EasyCorp\Bundle\EasyAdminBundle\Config\{Action, Actions, Crud, KeyValueStore};
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Component\Validator\Constraints\Choice;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use Symfony\Component\Form\Extension\Core\Type\{PasswordType, RepeatedType};
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\{FormBuilderInterface, FormEvent, FormEvents};
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;


class UsuarioCrudController extends AbstractCrudController
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

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
                yield TextField::new('password')
                                                ->setFormType(RepeatedType::class)
                                                ->setFormTypeOptions([
                                                    'type' => PasswordType::class,
                                                    'first_options' => ['label' => 'Contraseña'],
                                                    'second_options' => ['label' => 'Repetir contraseña'],
                                                    'mapped' => false,
                                                ]),    
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

    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    private function addPasswordEventListener(FormBuilderInterface $formBuilder): FormBuilderInterface
    {
        return $formBuilder->addEventListener(FormEvents::POST_SUBMIT, $this->hashPassword());
    }

    private function hashPassword() 
    {
        return function($event) 
        {
            $form = $event->getForm();

            if (!$form->isValid()) 
            {
                return;
            }

            $password = $form->get('password')->getData();

            if ($password === null) 
            {
                return;
            }

            $user = $this->getUser();
            if ($user instanceof PasswordAuthenticatedUserInterface) {
                $hash = $this->passwordHasher->hashPassword($user, $password);
            }
            $form->getData()->setPassword($hash);
        };
    }
    
}
