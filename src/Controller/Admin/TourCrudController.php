<?php

namespace App\Controller\Admin;

use App\Entity\Tour;
use App\Entity\Ruta;
use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\QueryBuilder;

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

//     public function createEntityFormBuilder($entityInstance, $formOptions): FormInterface
// {
//     $formBuilder = parent::createEntityFormBuilder($entityInstance, $formOptions);

//     $usuarios = $this->entityManager->getRepository(Usuario::class)->findAll();
//     $guias = [];
//     foreach($usuarios as $usuario){
//         if (in_array("ROLE_GUIA", $usuario->getRoles())) {
//             $guias[$usuario->getNombre()] = $usuario;
//         }
//     }

//     $formBuilder->add('usuario', EntityType::class, [
//         'class' => Usuario::class,
//         'choices' => $guias,
//     ]);

//     return $formBuilder;
// }


    public function configureFields(string $pageName): iterable
    {

        return [
            AssociationField::new('ruta')
                                        ->setLabel('Ruta')
                                        ->setRequired(true)
                                        ->setHelp('Selecciona la ruta asociada al tour'),
            DateTimeField::new('fechaHora'),
            BooleanField::new('disponible'),
            AssociationField::new('usuario')
                                            ->setLabel('Guía')
                                            ->setQueryBuilder(function (QueryBuilder $queryBuilder) {
                                                $queryBuilder->andWhere("entity.roles like :role")
                                                ->setParameter('role', "%ROLE_GUIA%");
                                            })
                                            ->setRequired(true)
                                            ->setHelp('Selecciona el guía asociado al tour'),
        ];
        
        // return [
        //     AssociationField::new('ruta')
        //                                 ->setLabel('Ruta')
        //                                 ->setRequired(true)
        //                                 ->setHelp('Selecciona la ruta asociada al tour'),
        //     DateTimeField::new('fechaHora'),
        //     BooleanField::new('disponible'),
        //     AssociationField::new('usuario')
        //                                     ->setLabel('Guía')
        //                                     ->setFormType(ChoiceType::class)
        //                                     ->setFormTypeOptions([
        //                                         'choices' => $guias,
        //                                     ])
        //                                     ->setRequired(true)
        //                                     ->setHelp('Selecciona el usuario asociado al tour'),
        // ];
    }

    



}
