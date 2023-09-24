<?php

namespace Maris\Symfony\User\Security;

use Doctrine\ORM\EntityManagerInterface;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Maris\Symfony\User\Entity\User;
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

    private EntityManagerInterface $entityManager;
    public function __construct( UrlGeneratorInterface $urlGenerator  ,EntityManagerInterface $entityManager)
    {
        $this->urlGenerator = $urlGenerator;
        $this->entityManager = $entityManager;
        $this->phoneNumberUtil = PhoneNumberUtil::getInstance();
    }

    public function authenticate( Request $request ): Passport
    {
        $array = iterator_to_array( $request->request->getIterator() );
        //dump( $array );
        //$form =
        $phone = $array['login_form']["phone"];
        try {
            /*$number = $this->phoneUtil->parse( $login ,"ru");
            if($this->phoneUtil->isValidNumber($number)){
                $login = $this->phoneUtil->formatNationalNumberWithCarrierCode($number,"ru");
            }*/
            $login = $this->phoneNumberUtil->parse($phone,"ru");
        }catch ( \Exception $exception ){
            # Значит вход по email
            dump( $exception );
            throw $exception;
        }
        $request->getSession()->set(Security::LAST_USERNAME, $login);

        //dump($login);
        $repository = $this->entityManager->getRepository(User::class);
        $util = $this->phoneNumberUtil;
        return  new Passport(
            new UserBadge($login, function () use ($login,$repository,$util) {
                return $repository->findBy([
                    "phone" => $util->format($login, PhoneNumberFormat::E164)
                ]);
            }),
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