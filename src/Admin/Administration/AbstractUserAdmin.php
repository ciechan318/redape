<?php


namespace App\Admin\Administration;

use App\Service\ClientManager;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

abstract class AbstractUserAdmin extends AbstractAdministrationAdmin
{

    const ROUTE_PREFIX = 'users/';
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    public function __construct($code, $class, $baseControllerName, ClientManager $clientManager, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        parent::__construct($code, $class, $baseControllerName, $clientManager);
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function prePersist($object)
    {
        parent::prePersist($object);
        $object->setPassword($this->userPasswordEncoder->encodePassword($object, $object->getPassword()));
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('email');

        if (!$this->getSubject()->getId()) {
            $formMapper->add('password',
                RepeatedType::class,
                ['type' => PasswordType::class,
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