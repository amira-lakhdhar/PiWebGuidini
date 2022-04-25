<?php

namespace App\Form;

use App\Entity\Compagnieaerienne;
use App\Entity\Vol;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VolType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('num_vol')
            ->add('date_vol',DateType::class,[
                'widget' => 'single_text',
                'data' => new \DateTime("now")
            ])
            ->add('destination')
            ->add('ville_depart')
            ->add('type_vol',ChoiceType::class,[
                'choices'  => [
                    'Régulier.' => "Régulier.",
                    'Affrété' => "Affrété",
            ]])
            ->add('Compagnie',EntityType::class,[
                'class' => Compagnieaerienne::class,
                'choice_label'=> 'nom',
            ])
            ->add('Submit',SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vol::class,
        ]);
    }
}
