<?php

namespace Maris\Symfony\User\Controller;

use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Контроллер для выхода из системы.
 */
#[Route(path: '/logout', name: 'user_logout')]
class LogoutController extends AbstractController
{
    public function __invoke():never
    {
        throw new LogicException();
    }

}