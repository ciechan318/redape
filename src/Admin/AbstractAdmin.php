<?php


namespace App\Admin;

use App\Service\ClientManager;
use App\Service\LanguageManager;
use Sonata\AdminBundle\Admin\AbstractAdmin as BaseAbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

abstract class AbstractAdmin extends BaseAbstractAdmin
{

    const ROUTE_PREFIX = '/';

    /**
     * @var ClientManager
     */
    protected $clientManager;


    public function __construct($code, $class, $baseControllerName, ClientManager $clientManager, LanguageManager $languageManager)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->clientManager = $clientManager;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('show');
    }

}