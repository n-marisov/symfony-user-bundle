<?php

namespace Maris\Symfony\User\Form;

use Maris\Symfony\Person\Entity\Girl;
use Maris\Symfony\Person\Entity\Man;
use Maris\Symfony\Person\Entity\Person;
use Maris\Symfony\Person\Model\Gender;
use Maris\Symfony\User\Entity\User;
use Symfony\Component\Form\DataTransformerInterface;

/***
 * Преобразователь объекта персоны.
 */
class UserViewTransformer implements DataTransformerInterface
{

    /***
     * @param Person|null $value
     * @return array
     */
    public function transform( mixed $value ):array
    {
        return (empty($value)) ? [] : [
            'surname' => $value->getSurname(),
            'firstname' => $value->getFirstname(),
            'patronymic' => $value->getPatronymic(),
            'gender' => match ($value->getGender()){
                Gender::GIRL => -1,
                Gender::MAN => 1,
                default => 0
            },
            'birthDate' => $value->getBirthDate()
        ];
    }

    /**
     * @param array $value
     * @return Person|null
     */
    public function reverseTransform(mixed $value):?User
    {
        dump($value);
        return null;
    }
}