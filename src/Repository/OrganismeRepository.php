<?php

namespace App\Repository;

use App\Entity\Organisme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Organisme|null find($id, $lockMode = null, $lockVersion = null)
 * @method Organisme|null findOneBy(array $criteria, array $orderBy = null)
 * @method Organisme[]    findAll()
 * @method Organisme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganismeRepository extends ServiceEntityRepository
{
    const ORGANISME='o';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Organisme::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::ORGANISME)
            ->select(UserRepository::USER, self::ORGANISME)
            ->leftJoin(self::ORGANISME . '.users',UserRepository::USER)
            ->orderBy(self::ORGANISME . '.name', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllForUser(string $userId)
    {
        return $this->createQueryBuilder(self::ORGANISME)
            ->select(self::ORGANISME)
            ->leftJoin(self::ORGANISME . '.users' , UserRepository::USER )
            ->where(UserRepository::USER . '.id = :user')
            ->setParameter('user', $userId)
            ->orderBy(self::ORGANISME . '.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
