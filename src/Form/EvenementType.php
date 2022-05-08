<?php

namespace App\Form;

use App\Entity\Evenement;
use App\Entity\Offre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom')
            ->add('Date',DateTimeType::class)
            ->add('Type',ChoiceType::class,[
                'choices'  => [
                    'Festival' => "Festival",
                    'Carvale' => "Carvale",
                ]
            ])
            ->add('localisation')
            ->add('Image', FileType::class, array('data_class' => null))
            ->add('Image', FileType::class, array('data_class' => null,'required'=>true))
            ->add('Description',TextareaType::class,['required' => true ])
            ->add('Prix',NumberType::class)
            ->add('Offre',EntityType::class,[
                'class'=>Offre::class,
                'choice_label'=>'nom',
            ])
            ->add('Confirmer',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
