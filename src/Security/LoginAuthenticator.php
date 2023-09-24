<?php

namespace Maris\Symfony\User\Security;

use libphonenumber\PhoneNumberUtil;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
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

    public function __construct( UrlGeneratorInterface $urlGenerator  )
    {
        $this->urlGenerator = $urlGenerator;
        $this->phoneNumberUtil = PhoneNumberUtil::getInstance();
    }

    public function authenticate( Request $request ): Passport
    {
        $login = $request->request->get('phone', '');
        /*try {
            /*$number = $this->phoneUtil->parse( $login ,"ru");
            if($this->phoneUtil->isValidNumber($number)){
                $login = $this->phoneUtil->formatNationalNumberWithCarrierCode($number,"ru");
            }*/
        /*    $login = $this->phoneNumberUtil->parse($login,"ru");
        }catch ( \Exception $exception ){
            # Значит вход по email
            dump( $exception );
            throw $exception;
        }*/
        //$request->getSession()->set(Security::LAST_USERNAME, $login);

        dump($login);

        return  new Passport(
            new UserBadge($login),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName))
            return new RedirectResponse( $targetPath );
        return new RedirectResponse("/");
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate('user_login');
    }
}