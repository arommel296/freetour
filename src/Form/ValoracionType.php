<?php

namespace App\Form;

use App\Entity\Valoracion;
use App\Entity\reserva;
use App\Form\Type\EstrellaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ValoracionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('notaGuia', HiddenType::class)
            ->add('notaRuta', HiddenType::class)
            ->add('comentario')
            // ->add('notaGuia', ChoiceType::class, [
            //     'choices' => [
            //         '1' => 1,
            //         '2' => 2,
            //         '3' => 3,
            //         '4' => 4,
            //         '5' => 5,
            //     ],
            //     'expanded' => true,
            //     'multiple' => false,
            // ])
            // ->add('notaRuta', ChoiceType::class, [
            //     'choices' => [
            //         '1' => 1,
            //         '2' => 2,
            //         '3' => 3,
            //         '4' => 4,
            //         '5' => 5,
            //     ],
            //     'expanded' => true,
            //     'multiple' => false,
            // ])
            // ->add('notaRuta', EstrellaType::class, [
            //     'label' => 'Nota Ruta',
            // ])
            // ->add('notaGuia', EstrellaType::class, [
            //     'label' => 'Nota Guía',
            // ])
            // ->add('comentario')
//             ->add('reserva', EntityType::class, [
//                 'class' => reserva::class,
// 'choice_label' => 'id',
//             ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Valoracion::class,
        ]);
    }
}
