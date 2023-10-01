<?php

namespace Maris\Symfony\User\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Maris\Symfony\User\Entity\User;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

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

    private TranslatorInterface $translator;
    public function __construct( ManagerRegistry $registry, TranslatorInterface $translator )
    {
        parent::__construct( $registry, User::class );
        $this->phoneNumberUtil = PhoneNumberUtil::getInstance();
        $this->translator = $translator;
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

    /***
     * Получает пользователя по объекту номера телефона
     * @param PhoneNumber $phone
     * @return User|null
     */
    public function findByPhone( PhoneNumber $phone ):?User
    {
        try {
            return $this->loadUserByIdentifier(
                $this->phoneNumberUtil->format(
                    $phone, PhoneNumberFormat::E164
                )
            );
        }catch (\Exception $exception){
            return null;
        }
    }

    /***
     * Получает пользователя по номеру телефона.
     * @param string $identifier
     * @return User
     * @throws NonUniqueResultException
     * @throws UserNotFoundException
     */
    public function loadUserByIdentifier(string $identifier): User
    {

        try {
            $phone = $this->phoneNumberUtil->parse( $identifier );
            if( empty($phone) || !$this->phoneNumberUtil->isValidNumber($phone) )
                $phone = $this->phoneNumberUtil->parse( $identifier,$this->translator->getLocale() );
        }catch ( NumberParseException $exception ){
            $message = $this->translator->trans('user.login.exception.parse_number_phone');
            /*$message .= match ( $exception->getErrorType() ){
                NumberParseException::NOT_A_NUMBER => 'The phone number supplied was null',
                NumberParseException::TOO_LONG => 'The string supplied was too long to parse.',
                NumberParseException::NOT_A_NUMBER=> 'The string supplied did not seem to be a phone number.',
                NumberParseException::INVALID_COUNTRY_CODE => 'Missing or invalid default region.',
                NumberParseException::INVALID_COUNTRY_CODE => 'Could not interpret numbers after plus-sign.',
                default => ''
            };*/

            throw new UserNotFoundException( $message );
        }

        $user = $this->createQueryBuilder('u')
            ->andWhere('u.phone = :phone')
            ->setParameter('phone',  $this->phoneNumberUtil->format($phone, PhoneNumberFormat::E164) )
            ->getQuery()
            ->getOneOrNullResult();

        if(!empty($user))
            return $user;

        throw new UserNotFoundException($this->translator->trans('user.login.exception.not_registered'));
    }

    /***
     * Изменяет хеш пароля при изменении алгоритма в настройках безопасности.
     * @param PasswordAuthenticatedUserInterface $user
     * @param string $newHashedPassword
     * @return void
     */
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