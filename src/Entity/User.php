<?php

namespace Maris\Symfony\User\Entity;

use libphonenumber\PhoneNumber;
use Maris\interfaces\Person\AggregateNotNull\PersonAggregateNotNullInterface;
use Maris\interfaces\Person\Model\PersonInterface;
use Maris\Symfony\Person\Entity\Person;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/***
 * Пользователь.
 * В качестве идентификатора используется номер телефона.
 */
class User implements UserInterface, PersonAggregateNotNullInterface, PasswordAuthenticatedUserInterface
{

    private ?int $id = null;

    private PhoneNumber $phone;

    private Person $person;

    private array $roles = ["ROLE_USER"];

    private string $password;

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

    /**
     * @param PhoneNumber $phone
     * @return $this
     */
    public function setPhone(PhoneNumber $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @param Person $person
     * @return $this
     */
    public function setPerson(Person $person): self
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = array_unique( array_merge($roles,["ROLE_USER"]) );
        return $this;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }



}