<?php

namespace Maris\Symfony\User\Form;

use Maris\Symfony\User\Entity\User;
use Maris\Symfony\User\Repository\UserRepository;

class UserLoader
{
    private UserRepository $repository;
    public function __construct( UserRepository $repository )
    {
        $this->repository = $repository;
    }

    public function __invoke( string $login ):?User
    {
        //dump($login);
        return $this->repository->findByPhone( $login );
    }

}