<?php

namespace Maris\Symfony\User\Entity;

use libphonenumber\PhoneNumber;
use Maris\interfaces\Person\Model\PersonAggregateInterface;
use Maris\interfaces\Person\Model\PersonInterface;
use Maris\Symfony\Person\Entity\Person;
use Symfony\Component\Security\Core\User\UserInterface;

/***
 * Пользователь.
 * В качестве идентификатора используется номер телефона.
 */
class User implements UserInterface, PersonAggregateInterface
{

    private ?int $id = null;

    private PhoneNumber $phone;

    private Person $person;

    private array $roles;

    private string $password;

    /**
     * @param PhoneNumber $phone
     * @param Person $person
     * @param string $password
     * @param array|string[] $roles
     */
    public function __construct( PhoneNumber $phone, Person $person, string $password, array $roles = [] )
    {
        $this->phone = $phone;
        $this->person = $person;
        $this->roles = array_merge( $roles, ["role_user"] );
        $this->password = $password;
    }


    /**
     * @inheritDoc
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials():void
    {

    }

    /**
     * @inheritDoc
     */
    public function getUserIdentifier(): string
    {
        return $this->person->getFirstname();
    }

    public function getPerson(): PersonInterface
    {
        return $this->person;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return PhoneNumber
     */
    public function getPhone(): PhoneNumber
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }


}