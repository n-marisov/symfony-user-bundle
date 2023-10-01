<?php

namespace Maris\Symfony\User\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Maris\Symfony\Person\Entity\Person;
use Maris\Symfony\User\Entity\User;
use Maris\Symfony\User\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/***
 * Контроллер регистрации нового пользователя.
 */
#[Route("/registered", name: "user_registered", methods: ["GET","POST"]) ]
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
            if(!empty($user = $form->getData()) && is_a($user,User::class)){
                dump($user);
                $user->setPassword($hasher->hashPassword($user,$user->getPassword()));
                $em->persist($user);
                $em->flush();
            }
            return $this->redirectToRoute("user_account");
        }

        return $this->render('user.registered.html.twig',[
            "registeredForm" => $form->createView()
        ]);
    }
}