<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FrenchToDateTimeTransformer implements DataTransformerInterface
{
    public function transform($date)
    {

        if ($date === null) {
            return '';
        }

        return $date->format('d/m/Y H:i');
    }

    public function reverseTransform($frenchDate)
    {
        if ($frenchDate === null) {
            throw new TransformationFailedException("Vous devez fournir une date");
        }

        $date = \DateTime::createFromFormat('d/m/Y H:i', $frenchDate);

        if ($date === false) {
            throw new TransformationFailedException("Le format n'est pas le bon");
        }

        return $date;
    }
}
