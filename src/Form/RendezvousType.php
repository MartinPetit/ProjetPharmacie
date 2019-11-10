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

    
     /**
     * Permet d'avoir la configuration de base d'un champ
     * 
     * @param string $label
     * @param string $placeholder
     * @return array
     */
    
    private function getConfiguration($label, $placeholder) {
        return  [
            'label' => $label, 
        'attr' => [
            'placeholder' => $placeholder, 

            'widget' => 'single_text'
        ]
        ];
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Date', DateTimeType::class, $this->getConfiguration("date et heure du rendz vous", "La date à laquelle vous avez pris le rendez vous"))
            ->add('endDate', DateTimeType::class, $this->getConfiguration("date et heure de fin du rendez vous", "La date théorique de fin du rendez vous"))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Rendezvous::class,
        ]);
    }
}
