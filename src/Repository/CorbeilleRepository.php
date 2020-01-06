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
    const ALIAS = 'c';
    const ALIAS_ACTION_READERS = 'car';
    const ALIAS_ACTION_WRITERS = 'caw';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Corbeille::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                UserRepository::ALIAS,
                OrganismeRepository::ALIAS,
                CorbeilleRepository::ALIAS_ACTION_WRITERS,
                CorbeilleRepository::ALIAS_ACTION_READERS
            )
            ->leftJoin(self::ALIAS.'.users', UserRepository::ALIAS)
            ->leftJoin(self::ALIAS.'.organisme', OrganismeRepository::ALIAS)
            ->leftjoin(self::ALIAS.'.actionWriters', CorbeilleRepository::ALIAS_ACTION_WRITERS)
            ->leftjoin(self::ALIAS.'.actionReaders', CorbeilleRepository::ALIAS_ACTION_READERS)
            ->orderBy(self::ALIAS.'.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllForUser(string $userId)
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS)
            ->leftJoin(self::ALIAS . '.users' , UserRepository::ALIAS )
            ->where(UserRepository::ALIAS . '.id = :user')
            ->setParameter('user', $userId)
            ->orderBy(self::ALIAS . '.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
