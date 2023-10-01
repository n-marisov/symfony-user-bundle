<?php

namespace Maris\Symfony\User\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Maris\Symfony\User\Entity\User;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Репозиторий сущности пользователя.
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements UserLoaderInterface, PasswordUpgraderInterface
{
    private PhoneNumberUtil $phoneNumberUtil;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct( $registry, User::class );
        $this->phoneNumberUtil = PhoneNumberUtil::getInstance();
    }

    public function save( UserInterface $user, bool $flush = false ):void
    {
        if(!is_a($user,User::class))
            return;

        $this->getEntityManager()->persist( $user );

        if($flush)
            $this->getEntityManager()->flush();
    }

    public function remove( UserInterface $user, bool $flush = false ):void
    {
        if(!is_a($user,User::class))
            return;

        $this->getEntityManager()->remove( $user );
        if($flush)
            $this->getEntityManager()->flush();
    }

    public function findByPhone( PhoneNumber $phone ):?User
    {
        return $this->loadUserByIdentifier(
            $this->phoneNumberUtil->format(
                $phone, PhoneNumberFormat::E164
            )
        );
    }

    public function loadUserByIdentifier(string $identifier): User
    {
        try {
            $identifier = $this->phoneNumberUtil->parse($identifier,"ru");
            $identifier = $this->phoneNumberUtil->format($identifier, PhoneNumberFormat::E164);

            return $this->createQueryBuilder('u')
                ->andWhere('u.phone = :phone')
                ->setParameter('phone', $identifier )
                ->getQuery()
                ->getOneOrNullResult() ??
                throw new UserNotFoundException("Пользователя с таким номер не существует");
        }catch ( \Exception $exception ){
            throw new UserNotFoundException("Ошибка в номере телефона");
        }
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        // установить новый хэшированный пароль в объекте User
        if( is_a($user,User::class) )
            $this->save( $user->setPassword($newHashedPassword),true );
    }


    //    /**
//     * @return TestEntity[] Returns an array of TestEntity objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TestEntity
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}