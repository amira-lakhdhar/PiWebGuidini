<?php

namespace App\Form;

use App\Entity\EventRate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventRateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rate',ChoiceType::class,[
                'choices'=>["1"=>"1",
                    "2"=>"2",
                    "3"=>"3",
                    "4"=>"4",
                    "5"=>"5"],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EventRate::class,
        ]);
    }
}
