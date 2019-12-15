<?php

namespace App\Repository;

use App\Entity\Corbeille;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Corbeille|null find($id, $lockMode = null, $lockVersion = null)
 * @method Corbeille|null findOneBy(array $criteria, array $orderBy = null)
 * @method Corbeille[]    findAll()
 * @method Corbeille[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CorbeilleRepository extends ServiceEntityRepository
{
    const CORBEILLE = 'c';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Corbeille::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::CORBEILLE)
            ->select(self::CORBEILLE, UserRepository::USER, OrganismeRepository::ORGANISME)
            ->leftJoin(self::CORBEILLE.'.users', UserRepository::USER)
            ->leftJoin(self::CORBEILLE.'.organisme', OrganismeRepository::ORGANISME)
            ->orderBy(self::CORBEILLE.'.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllForUser(string $userId)
    {
        return $this->createQueryBuilder(self::CORBEILLE)
            ->select(self::CORBEILLE)
            ->leftJoin(self::CORBEILLE . '.users' , UserRepository::USER )
            ->where(UserRepository::USER . '.id = :user')
            ->setParameter('user', $userId)
            ->orderBy(self::CORBEILLE . '.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
