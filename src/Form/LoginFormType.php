<?php

namespace Maris\Symfony\User\Form;

use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Форма входа.
 */
class LoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $builder
            ->add("phone",PhoneNumberType::class,[
                "default_region"=>"RU",
                "label" => "user.login.phone",
                "constraints"=> [
                    new NotBlank([
                        "message" => "Поле не может быть пустым !"
                    ]),
                    new PhoneNumber()
                ]
            ])
            ->add("password",PasswordType::class,[
                "label" => "user.login.password",
                "constraints"=> [
                    new NotBlank([
                        "message" => "Вход без пароля недоступен !"
                    ])
                ]
            ])
            ->add("submit",SubmitType::class,[
                "label" => 'user.login.submit'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            // the name of the hidden HTML field that stores the token
            'csrf_field_name' => '_token',
            // an arbitrary string used to generate the value of the token
            // using a different string for each form improves its security
            'csrf_token_id'   => 'authenticate',
        ]);
    }


}