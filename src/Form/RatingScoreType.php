<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RatingScoreType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'multiple' => false,
                'expanded' => true,
                'choices' => [
                    '0.5' => '0.5',
                    '1.0' => '1.0',
                    '1.5' => '1.5',
                    '2.0' => '2.0',
                    '2.5' => '2.5',
                    '3.0' => '3.0',
                    '3.5' => '3.5',
                    '4.0' => '4.0',
                    '4.5' => '4.5',
                    '5.0' => '5.0',
                ]
            ]
        );
    }

    public function getBlockPrefix()
    {
        return 'rating_score';
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}