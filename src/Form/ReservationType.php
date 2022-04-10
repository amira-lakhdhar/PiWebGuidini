<?php

namespace App\Form;

use App\Entity\Hebergement;
use App\Entity\Reservation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date')
            ->add('duree')
            ->add('etat')
            ->add('payement')
            ->add('user', EntityType::class,
                [
                    'class'                 => User::class,
                    'choice_label'          => 'nom',
                    'multiple'              => false,
                ])
            ->add('hebergement', EntityType::class,
                [
                    'class'                 => Hebergement::class,
                    'choice_label'          => 'nom',
                    'multiple'              => false,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
