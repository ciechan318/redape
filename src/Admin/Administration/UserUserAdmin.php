<?php


namespace App\Admin\Administration;

use Sonata\AdminBundle\Route\RouteCollection;

final class UserUserAdmin extends AbstractUserAdmin
{
    protected $baseRouteName = 'admin_app_user_user';
    protected $baseRoutePattern = self::ROUTE_PREFIX . 'users';

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        $query
            ->andWhere($query->getRootAlias() . '.roles NOT LIKE :roles')
            ->setParameter('roles', '%ROLE_ADMIN%');

        return $query;
    }


}