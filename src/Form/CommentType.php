<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Movie;
use App\ModelTransformer\MovieTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    /**
     * @var MovieTransformer
     */
    private $movieTransformer;

    public function __construct(MovieTransformer $movieTransformer)
    {
        $this->movieTransformer = $movieTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('commenter');
        $builder->add('text');
        $builder->add('movie', HiddenType::class);
        $builder->get('movie')->addModelTransformer($this->movieTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
