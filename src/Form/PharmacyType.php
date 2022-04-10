<?php

namespace App\Form;

use App\Entity\Pharmacy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PharmacyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('location')
            ->add('hourly',ChoiceType::class,['choices'  => [
                'Day' => "Day",
                'Night' => "Night",

            ]])
            ->add('phone')
            ->add('email',EmailType::class)
            ->add('Ajouter',SubmitType::class)
            ->add('cancel',ResetType::class);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pharmacy::class,
        ]);
    }
}
