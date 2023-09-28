<?php

namespace Maris\Symfony\User\Controller;


use Maris\Symfony\User\Repository\UserRepository;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/***
 * Контролер для удаления пользователя
 */
#[Route("/user/delete",methods: ["POST"])]
class DeleteUserController extends AbstractController
{
    private UserRepository $userRepository;
    public function __construct( UserRepository $userRepository )
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke():Response
    {
        $this->userRepository->remove($this->getUser());

        return new Response();
    }
}