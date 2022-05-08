<?php

namespace App\Form;

use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints\ValidCaptcha;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ForgetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',RepeatedType::class,[
                'type'=>EmailType::class,
                'invalid_message'=>"Les addresses Email doivent etre identiques",
                'required'=>true,
                'constraints'=>[
                    new NotBlank(),
                    new Email()
                ],
                'first_options'=>[
                    'label'=>'Saisir votre adresse mail'
                ],
                'second_options'=>[
                    'label'=>'Confirmer votre addresse mail'
                    ]
            ])
            ->add('CaptchaCode',CaptchaType::class,[
                    'captchaConfig'=>'ExampleCaptchaUserRegistration',
                    'constraints'=>[
                        new ValidCaptcha([
                            'message'=>'Invalid captcha, Please try again'
                        ])
                    ]
                ]

            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
