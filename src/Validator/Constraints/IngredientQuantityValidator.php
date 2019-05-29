<?php

namespace App\Validator\Constraints;

use App\Entity\IngredientQuantity as EntityIngredientQuantity;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class IngredientQuantityValidator extends ConstraintValidator
{
    public function validate($protocol, Constraint $constraint)
    {
        if (!$constraint instanceof IngredientQuantity) {
            throw new UnexpectedTypeException($constraint, IngredientQuantity::class);
        }

        /**
         * @var EntityIngredientQuantity $protocol
         */

        $recipe = $protocol->getRecipe();
        $ingredient = $protocol->getIngredient();

        if ($recipe || $ingredient) {
            $occurrences = 0;
            foreach ($recipe->getIngredientQuantities() as $ingredientQuantity) {
                if ($ingredientQuantity->getIngredient() === $ingredient) {
                    $occurrences++;
                }

                if ($occurrences > 1) {
                    $this->context->buildViolation($constraint->message)
                        ->atPath('ingredient')
                        ->addViolation();

                    return;
                }
            }
        }
    }
}