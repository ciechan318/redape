<?php


namespace App\Admin\Administration;

final class UserAdministratorAdmin extends AbstractUserAdmin
{

    protected $baseRouteName = 'admin_app_user_administrator';
    protected $baseRoutePattern = self::ROUTE_PREFIX . 'administrators';

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        $query
            ->andWhere($query->getRootAlias() . '.roles LIKE :roles')
            ->setParameter('roles', '%ROLE_ADMIN%');

        return $query;
    }

    public function getNewInstance()
    {
        $object = parent::getNewInstance();
        $object->setRoles(['ROLE_ADMIN']);

        return $object;

    }


}