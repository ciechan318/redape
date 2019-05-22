<?php

namespace App\Translator;

use Sonata\AdminBundle\Translator\LabelTranslatorStrategyInterface;

class ProjectLabelTranslatorStrategy implements LabelTranslatorStrategyInterface
{
    public function getLabel($label, $context = '', $type = '')
    {
        $label = str_replace('.', '_', $label);

        return sprintf('%s_%s', $type, preg_replace('~(?<=\\w)([A-Z])~', ucfirst('$1'), $label));
    }
}
