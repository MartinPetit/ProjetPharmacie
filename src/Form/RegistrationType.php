<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ConfigType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends ConfigType
{

     


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration("Prénom", "Entrer votre prénom"))
            ->add('lastName', TextType::class, $this->getConfiguration("Nom", "Entrer votre nom"))
            ->add('email', EmailType::class, $this->getConfiguration("Email","Entrer votre email"))
            ->add('hash', PasswordType::class, $this->getConfiguration("Mot de passe", "Entrer votre mot de passe"))
            ->add('passwordConfirm', PasswordType::class, $this->getConfiguration("Confirmer le mot de passe", "Entrer de nouveau le mot de passe"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
