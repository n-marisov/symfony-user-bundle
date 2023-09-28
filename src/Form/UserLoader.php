<?php

namespace Maris\Symfony\User\Form;

use Maris\Symfony\User\Entity\User;
use Maris\Symfony\User\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class UserLoader
{
    private UserRepository $repository;
    public function __construct( UserRepository $repository )
    {
        $this->repository = $repository;
    }

    public function __invoke( string $login ):User
    {
        return $this->repository->loadUserByIdentifier( $login );
    }

}