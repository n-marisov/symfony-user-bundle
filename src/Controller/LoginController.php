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
#[Route(path: '/login', name: 'user_login')]
class LoginController extends AbstractController
{
    /***
     * Авторизация по паролю.
     * @param Request $request
     * @return Response
     */
    public function __invoke( Request $request ):Response
    {
        $form = $this->createForm( RegistrationFormType::class )
            ->handleRequest( $request );

        // Форма отправлена и валидна.
       /* if($form->isSubmitted() && $form->isValid() )
        {

        }*/

        // Форма не отправлена.
        return $this->render("@MarisUserBundle/login/index.html.twig",[

        ]);
    }
}