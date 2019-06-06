<?php


namespace App\Admin\Administration;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class SettingsAdmin extends AbstractAdministrationAdmin
{

    protected $baseRoutePattern = '/settings';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(
            [
                'list',
                'edit',
            ]
        );
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('type', TextType::class, ['mapped' => false, 'disabled' => true, 'data' => $this->trans($this->getSubject()->getHumanType())])
            ->add('body', CKEditorType::class);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('humanType', null, ['template' => 'admin/list_trans.html.twig', 'label' => 'label_type']);
    }
}