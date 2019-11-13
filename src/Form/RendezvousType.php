<?php

namespace App\Form;

use App\Entity\Rendezvous;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class RendezvousType extends AbstractType
{

    
     

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Date', DateTimeType::class, [
                "label" => "Choisissez votre rendezvous",
                "date_widget" => "single_text",
                "time_widget" => "single_text", 
                
            ])
            

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Rendezvous::class,
        ]);
    }
}
