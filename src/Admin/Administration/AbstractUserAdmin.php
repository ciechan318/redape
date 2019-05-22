<?php


namespace App\Admin\Administration;

use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

abstract class AbstractUserAdmin extends AbstractAdministrationAdmin
{

//    @TODO custom action for password change
//    @TODO logout action for admin

    const ROUTE_PREFIX = '/users/';

    public function prePersist($object)
    {
        parent::prePersist($object);
        $this->clientManager->saveUserPassword($object, $this->getForm()->get('plainPassword')->getData(), false);
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
        $listMapper->addIdentifier('email');
    }

}