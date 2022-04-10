<?php

namespace App\Form;

use App\Entity\Avis;
use App\Entity\Place;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rate')
            ->add('Place', EntityType::class,
                [
                    'class'                 => Place::class,
                    'choice_label'          => 'nom',
                    'multiple'              => false,
                ])
            ->add('user', EntityType::class,
                [
                    'class'                 => User::class,
                    'choice_label'          => 'nom',
                    'multiple'              => false,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
        ]);
    }
}
