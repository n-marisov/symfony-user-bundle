<?php

namespace Maris\Symfony\User\Form;

use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Форма входа.
 */
class LoginFormType extends AbstractType
{

    private TranslatorInterface $translator;
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $builder
            ->add("phone",PhoneNumberType::class,[
                "default_region"=>"RU",
                "label" => $this->translator->trans("user.phone")
            ])
            ->add("password",PasswordType::class)
            ->add("submit",SubmitType::class);
    }
}