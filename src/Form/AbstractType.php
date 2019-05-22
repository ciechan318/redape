<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType as BaseAbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbstractType extends BaseAbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label_format' => 'label_%name%',
        ]);
    }
}
