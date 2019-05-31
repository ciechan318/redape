<?php


namespace App\Admin\Administration;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Vich\UploaderBundle\Form\Type\VichImageType;

final class IngredientAdmin extends AbstractAdministrationAdmin
{

    protected $baseRoutePattern = '/ingredients';

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('imageFile', VichImageType::class, ['download_label' => 'action_downloadFullSize', 'imagine_pattern' => 'admin_preview']);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('imageFile', null, ['template' => 'admin/list_image.html.twig']);
    }
}