<?php

namespace Maris\Symfony\User\Form;

use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Форма входа.
 */
class LoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $builder->add("phone",PhoneNumberType::class,["default_region"=>"RU"])
            ->add("password",PasswordType::class);
    }
}