<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Security\Core\Security;


class ClientManager
{

    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getUser(): ?User
    {
        return $this->security->getUser();
    }
}
