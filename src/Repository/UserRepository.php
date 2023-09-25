<?php

namespace Maris\Symfony\User\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Maris\Symfony\User\Entity\User;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    private PhoneNumberUtil $phoneNumberUtil;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct( $registry, User::class );
        $this->phoneNumberUtil = PhoneNumberUtil::getInstance();
    }

    public function save( User $user, bool $flush = false ):void
    {
        $this->getEntityManager()->persist( $user );
        if($flush)
            $this->getEntityManager()->flush();
    }

    public function remove( User $user, bool $flush = false ):void
    {
        $this->getEntityManager()->remove( $user );
        if($flush)
            $this->getEntityManager()->flush();
    }

    public function findByPhone( PhoneNumber|string $phone ):?User
    {
        if(is_string($phone))
            $phone = $this->phoneNumberUtil->parse($phone,"ru");

        return $this->createQueryBuilder('u')
            ->andWhere('u.phone = :phone')
            ->setParameter('phone', $this->phoneNumberUtil->format($phone,PhoneNumberFormat::E164) )
            ->getQuery()
            ->getOneOrNullResult();
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