<?php

namespace Maris\Symfony\User\Security;

use Doctrine\ORM\EntityManagerInterface;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Maris\Symfony\User\Entity\User;
use Maris\Symfony\User\Form\LoginFormType;
use Maris\Symfony\User\Form\UserLoader;
use Maris\Symfony\User\Repository\UserRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'user_login';

    private UrlGeneratorInterface $urlGenerator;

    private PhoneNumberUtil $phoneNumberUtil;

    private EntityManagerInterface $entityManager;

    private FormFactoryInterface $formFactory;

    private UserRepository $userRepository;

    private  AuthenticationSuccessHandlerInterface $successHandler;
    public function __construct( AuthenticationSuccessHandlerInterface $successHandler, UrlGeneratorInterface $urlGenerator, UserRepository $userRepository  ,EntityManagerInterface $entityManager, FormFactoryInterface $formFactory )
    {
        $this->urlGenerator = $urlGenerator;
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->userRepository = $userRepository;
        $this->successHandler = $successHandler;
        $this->phoneNumberUtil = PhoneNumberUtil::getInstance();
    }

    public function authenticate( Request $request ): Passport
    {
        $form = $this->formFactory->create( LoginFormType::class )->handleRequest($request);
        /**@var PhoneNumber $login **/
        $login = $form->get("phone")->getData();
        $password = $form->get("password")->getData();
        //$token =  $form->get("_token")->getData();
        $request->getSession()->set(Security::LAST_USERNAME, $login->getNationalNumber() );




        return  new Passport(
            new UserBadge( $login->getNationalNumber() , new UserLoader( $this->userRepository ) ),
            new PasswordCredentials( $password ),
            [
               // new CsrfTokenBadge('authenticate', $request->get("_token")->getData() ),
            ]
        );
    }




    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        /**return $this->successHandler->onAuthenticationSuccess($request,$token);
        //return null;
        /*if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName))
            return new RedirectResponse( $targetPath );
        //return new RedirectResponse( $this->getTargetPath($request->getSession(), $firewallName) );
        */
        return new RedirectResponse("/");
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate('user_login');
    }
}