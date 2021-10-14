<?php

namespace App\Form;

use App\Entity\Movie;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('imdb');

        if ($options['include_title'] ?? false) {
            $builder->add('title', TextType::class, [
                'help' => 'Remove to sync with OMDb. Edit to modify OMDb title.'
            ]);
        }

        $builder->add('yearFeasted');
        $builder->add('startTime', DateTimeType::class, [
            'html5' => true,
            'widget' => 'single_text',
        ]);
        $builder->add('lukeBit', CKEditorType::class, [
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Movie::class,
                'include_title' => false,
            ]
        );
    }
}
