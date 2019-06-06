<?php


namespace App\Admin\Administration;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Vich\UploaderBundle\Form\Type\VichImageType;

final class RecipeImageAdmin extends AbstractAdministrationAdmin
{

    protected $baseRoutePattern = '/recipe-image';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['create', 'edit', 'delete']);
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('imageFile', VichImageType::class, ['required' => false, 'download_label' => 'action_downloadFullSize', 'imagine_pattern' => 'admin_preview', 'allow_delete' => false]);
    }

}