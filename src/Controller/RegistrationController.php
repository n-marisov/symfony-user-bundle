<?php

namespace Maris\Symfony\User\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Maris\Symfony\Person\Entity\Person;
use Maris\Symfony\User\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/***
 * Контроллер регистрации нового пользователя.
 */
#[Route("/registered", name: "user_registered", methods: ["GET"]) ]
class RegistrationController extends AbstractController
{
    /**
     * Регистрация пользователя html.
     * @param Request $request
     * @param UserPasswordHasherInterface $hasher
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function __invoke( Request $request, UserPasswordHasherInterface $hasher , EntityManagerInterface $em ):Response
    {

        $form = $this->createForm(RegistrationFormType::class)
            ->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){
            # Кнопка нажата и форма валидна
            $person = new Person();
        }

        return $this->render('user.registered.html.twig',[
            "formType" => $form
        ]);
    }
}