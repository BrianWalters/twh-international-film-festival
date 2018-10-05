<?php

namespace App\Form;

use App\Entity\Rating;
use App\ModelTransformer\MovieTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RatingType extends AbstractType
{
    private $movieTransformer;

    public function __construct(MovieTransformer $movieTransformer)
    {
        $this->movieTransformer = $movieTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('rater', null, [
            'label' => 'Your name'
        ]);
        $builder->add('score', ChoiceType::class, [
            'choices' => [
                'half' => 0.5,
                'one' => 1,
            ],
            'multiple' => false,
            'expanded' => true,
        ]);
        $builder->add('movie', HiddenType::class);
        $builder->get('movie')->addModelTransformer($this->movieTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Rating::class,
        ]);
    }
}
