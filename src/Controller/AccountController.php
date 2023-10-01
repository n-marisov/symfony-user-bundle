<?php

namespace Maris\Symfony\User\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Контролер личного кабинета пользователя.
 */
//#[Route(path: '/account', name: 'user_account')]
class AccountController extends AbstractController
{
    public function __invoke( Request $request ):Response
    {
        return $this->render("user.account.html.twig",[

        ]);
    }
}