<?php

namespace Maris\Symfony\User\Controller;


use Maris\Symfony\User\Repository\UserRepository;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

/***
 * Контролер для удаления пользователя
 */
//#[Route("/user/delete/{id}",methods: ["GET","POST"])]
class DeleteUserController extends AbstractController
{
    private UserRepository $userRepository;
    public function __construct( UserRepository $userRepository )
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke( TokenStorageInterface $tokenStorage, SessionInterface $session, ?int $id = null ):Response
    {
        if($this->isGranted("USER_ADMIN", $this->getUser() )){
            # Удаляем любого пользователя
            $user = isset($id) ? $this->userRepository->find($id) : $this->getUser();
            $this->userRepository->remove( $user,true );
            return new Response();
        }
        # Удаляем собственный аккаунт и выходим из системы.
        $this->userRepository->remove($this->getUser(),true);
        $tokenStorage->removeToken(Security::LAST_USERNAME);
        #$tokenStorage->setToken(Security::LAST_USERNAME,null);
        $session->invalidate();

        return $this->redirectToRoute("user_logout");
    }
}