<?php


namespace App\Admin\Administration;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;

final class IngredientQuantityAdmin extends AbstractAdministrationAdmin
{

    protected $baseRoutePattern = self::ROUTE_PREFIX . 'ingredient-quantity';

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('ingredient', ModelType::class);
        $formMapper->add('quantity');
    }

}