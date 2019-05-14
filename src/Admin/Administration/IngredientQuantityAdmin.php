<?php


namespace App\Admin\Administration;

use Sonata\AdminBundle\Form\FormMapper;

final class IngredientQuantityAdmin extends AbstractAdministrationAdmin
{

    protected $baseRoutePattern = self::ROUTE_PREFIX . 'ingredient-quantity';

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('ingredient');
        $formMapper->add('quantity');
    }

}