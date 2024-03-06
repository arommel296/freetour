<?php

namespace App\Form;

use App\Entity\Reserva;
use App\Entity\Ruta;
use App\Entity\Tour;
use App\Entity\Valoracion;
use App\Entity\Usuario;
// use Doctrine\DBAL\Types\IntegerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class ReservaType extends AbstractType
{
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManagerInterface) {
        $this->entityManager = $entityManagerInterface;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $rutaId = $options['ruta_id'];

        $ruta = $this->entityManager->getRepository(Ruta::class)->find($rutaId);
        $tours = $ruta->getTours();
        $options['tours'] = $tours;



        $builder
            ->add('nEntradas', IntegerType::class, [
                'constraints' => [
                    new Range([
                        'min' => 1,
                        'max' => 8,
                        'notInRangeMessage' => 'El número de entradas debe ser 1 o más.',
                    ]),
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reserva::class,
            'ruta_id' => null,
            'tours' => null,
        ]);
    }
}
