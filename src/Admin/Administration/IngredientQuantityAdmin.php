<?php


namespace App\Admin\Administration;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Route\RouteCollection;

final class IngredientQuantityAdmin extends AbstractAdministrationAdmin
{

    protected $baseRoutePattern = '/ingredient-quantity';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['create', 'edit', 'delete']);
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('ingredient', ModelType::class);
        $formMapper->add('quantity');
    }

}