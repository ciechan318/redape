<?php


namespace App\Admin\Administration;

use App\Service\ClientManager;
use Sonata\AdminBundle\Admin\AbstractAdmin;

abstract class AbstractAdministrationAdmin extends AbstractAdmin
{

    const ROUTE_PREFIX = '/';

    /**
     * @var ClientManager
     */
    protected $clientManager;

    public function __construct($code, $class, $baseControllerName, ClientManager $clientManager)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->clientManager = $clientManager; //@TODO not used for now, just checking autowiring
    }

}