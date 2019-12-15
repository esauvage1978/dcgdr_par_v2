<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    const USER = 'u';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::USER)
            ->select(self::USER, OrganismeRepository::ORGANISME, CorbeilleRepository::CORBEILLE)
            ->leftJoin(self::USER.'.organismes',OrganismeRepository::ORGANISME)
            ->leftJoin(self::USER.'.corbeilles',CorbeilleRepository::CORBEILLE)
            ->orderBy(self::USER . '.name', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
}
