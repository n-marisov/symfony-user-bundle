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
            ->add('phone',PhoneNumberType::class,[
                'default_region'=>"RU",
                'label' => 'user.registration.phone'
            ])
            ->add("person",PersonFormType::class,[
                'label' => 'user.registration.person'
            ])
            ->add("password",PasswordType::class,[
                'label' => 'user.registration.password'
            ])
            ->add("submit",SubmitType::class,[
                'label' => 'user.registration.submit'
            ]);
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