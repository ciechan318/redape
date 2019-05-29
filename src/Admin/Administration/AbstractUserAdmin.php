<?php


namespace App\Admin\Administration;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

abstract class AbstractUserAdmin extends AbstractAdministrationAdmin
{

    const ROUTE_PREFIX = '/users/';

    public function prePersist($object)
    {
        parent::prePersist($object);
        $this->clientManager->saveUserPassword($object, $this->getForm()->get('plainPassword')->getData(), false);
    }

    public function getActionButtons($action, $object = null)
    {
        $list = parent::getActionButtons($action, $object);

        if ($action === 'changePassword') {
            $list['list'] = ['template' => '@SonataAdmin/Button/list_button.html.twig'];
        }

        return $list;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('changePassword', $this->getRouterIdParameter() . '/change-password');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('email');

        if (!$this->getSubject()->getId()) {
            $formMapper->add('plainPassword',
                RepeatedType::class,
                ['mapped' => false,
                    'type' => PasswordType::class,
                    'first_options' => ['label' => 'label_password'],
                    'second_options' => ['label' => 'label_password_confirmation']
                ]);
        }
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('email');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('email')
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                    'clone' => [
                        'template' => 'admin/list__action_changePassword.html.twig',
                    ],
                ],
            ]);
    }

}