<?php

namespace Maris\Symfony\User\Form;

use Maris\Symfony\User\Entity\User;
use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $builder
            ->add('phone',PhoneNumberType::class,['default_region'=>"RU"])
            ->add("person",PersonFormType::class)
            ->add("password",PasswordType::class)
            ->add("submit",SubmitType::class);
        $builder->get("person")->addViewTransformer( new PersonViewTransformer() );
        //$builder->addViewTransformer( new UserViewTransformer() );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => User::class
        ]);
    }
}