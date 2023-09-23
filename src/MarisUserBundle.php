<?php

namespace Maris\Symfony\User;

use Maris\Symfony\User\DependencyInjection\MarisUserExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class MarisUserBundle extends AbstractBundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new MarisUserExtension();
    }
}