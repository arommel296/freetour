<?php

namespace App\Form;

use App\Entity\Reserva;
use App\Entity\Ruta;
use App\Entity\Tour;
use App\Entity\Valoracion;
use App\Entity\Usuario;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

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
            ->add('nEntradas')

            // ->add('nEntradas', NumberType::class, [
            //     // 'type' => NumberType::class,
            //     'invalid_message' => 'Debe ser un número entero',
            //     'required' => true,
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'Por favor introduce un número entero positivo',
            //         ]),
            //         new Length([
            //             'min' => 1,
            //             'minMessage' => 'El número de entradas no puede ser menor de 1',
            //             'max' => 1,
            //         ]),
            //     ]
            // ])

            // ->add('nAsistentes')
            // ->add('fechaReserva')
            // ->add('tour', EntityType::class, [
            //     'class' => Tour::class,
            //     // 'choice_label' => function() use ($rutaId){
            //     //     $ruta = $this->entityManager->getRepository(Ruta::class)->find($rutaId);
            //     //     $tours = $ruta->getTours();
            //     //     // return $tour->getFechaHora()->format('Y-m-d H:i');
            //     //     foreach ($tours as $tour) {
            //     //         return $tour;
            //     //     } 
            //     // },
            //     'choices' => $tours,
            // ])
//             ->add('valoracion', EntityType::class, [
//                 'class' => Valoracion::class,
// 'choice_label' => 'id',
//             ])
//             ->add('usuario', EntityType::class, [
//                 'class' => Usuario::class,
// 'choice_label' => 'id',
//             ])
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
