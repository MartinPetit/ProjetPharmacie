<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ConfigType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\EqualTo;

class ResetPasswordType extends AbstractType
{




    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', PasswordType::class, [
                'label' => 'Nouveau mot de passe', 
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 6])
                ],
                'attr' => [
                    'placeholder' => 'Entrer votre nouveau mot de passe'
                ]
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'Confirmation mot de passe ', 
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 6]),
                    new EqualTo('password')
                ],
                'attr' => [
                    'placeholder' => 'Confirmer votre mot de passe'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                
            ]);
    }
}
