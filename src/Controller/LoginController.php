<?php

namespace Maris\Symfony\User\Controller;

use LogicException;
use Maris\Symfony\User\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/***
 * Контроллер для авторизации пользователя.
 */
class LoginController extends AbstractController
{
    /***
     * Авторизация по паролю.
     * @param Request $request
     * @return Response
     */
    #[Route(path: '/password/login', name: 'user_password_login')]
    public function loginPassword( Request $request ):Response
    {
        $form = $this->createForm( RegistrationFormType::class )
            ->handleRequest( $request );

        // Форма отправлена и валидна.
       /* if($form->isSubmitted() && $form->isValid() )
        {

        }*/

        // Форма не отправлена.
        return $this->render("user.login.html.twig",[

        ]);
    }


    /***
     * Выход из системы.
     * @return void
     */
    #[Route(path: '/logout', name: 'user_logout')]
    public function logout(): void
    {
        throw new LogicException();
    }
}