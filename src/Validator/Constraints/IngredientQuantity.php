<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IngredientQuantity extends Constraint
{
    public $message = 'validator_ingredient_already_present_in_recipe';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}