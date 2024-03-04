<?php
// src/Form/Type/StarRatingType.php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EstrellaType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => [
                '★' => 1,
                '★★' => 2,
                '★★★' => 3,
                '★★★★' => 4,
                '★★★★★' => 5,
            ],
            'expanded' => true,
            'multiple' => false,
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}