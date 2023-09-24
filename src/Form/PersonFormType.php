<?php

namespace Maris\Symfony\User\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/***
 * Форма с полями персоны.
 */
class PersonFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $builder
            ->add('surname',TextType::class,[
                "label"=>"Фамилия",
                "attr"=>[
                    "class"=>"form-control",
                    "placeholder"=>"Фамилия",
                    "autocomplete"=>"off"
                ],
            ])
            ->add('firstname',TextType::class,[
                "label"=>"Имя",
                "attr"=>[
                    "class"=>"form-control",
                    "placeholder"=>"Имя",
                    "autocomplete"=>"off"
                ],
            ])
            ->add('patronymic',TextType::class,[
                "label"=>"Отчество",
                "attr"=>[
                    "class"=>"form-control",
                    "placeholder"=>"Отчество",
                    "autocomplete"=>"off"
                ],
            ])

            ->add('gender',ChoiceType::class,[
                "label"=>"Пол",
                "attr"=>[
                    "class"=>"form-control",
                    "placeholder"=>"Пол",
                    "autocomplete"=>"off"
                ],
                "choices"=>[
                    "Укажите пол"=> 0,
                    "Мужчина"=>1,
                    "Женщина"=>-1
                ],
                "required"=>true,
            ])
            ->add('birthdate',DateType::class,[
                "label"=>"Дата рождения",
                "attr"=>[
                    "class"=>"form-control",
                    "placeholder"=>"Дата рождения",
                    "autocomplete"=>"off"
                ],
                'widget' => 'single_text',
                'input'  => 'datetime_immutable'
            ]);
    }
}