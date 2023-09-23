<?php

namespace Maris\Symfony\User\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $builder
            ->add('phone')
            ->add("person",PersonFormType::class)
            ->add("password",PasswordType::class);
        $builder->get("person")->addViewTransformer( new PersonViewTransformer() );
    }
}