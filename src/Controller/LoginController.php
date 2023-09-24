<?php

namespace Maris\Symfony\User\Controller;

use LogicException;
use Maris\Symfony\User\Form\LoginFormType;
use Maris\Symfony\User\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/***
 * Контроллер для авторизации пользователя.
 */
#[Route(path: '/login', name: 'user_login')]
class LoginController extends AbstractController
{
    /***
     * Авторизация по паролю.
     * @param AuthenticationUtils $utils
     * @param Request $request
     * @return Response
     */
    public function __invoke( AuthenticationUtils $utils, Request $request ):Response
    {
        $form = $this->createForm( LoginFormType::class )
            ->handleRequest( $request );


        // Форма отправлена и валидна.
        if($form->isSubmitted() && $form->isValid() )
        {
            // получите сообщение об ошибке входа в систему, если таковая имеется
            $error = $utils->getLastAuthenticationError();

            // последнее имя пользователя, введенное пользователем
            $lastUsername = $utils->getLastUsername();
        }

        // Форма не отправлена.
        return $this->render("user.login.html.twig",[
            "loginForm" => $form
        ]);
    }
}