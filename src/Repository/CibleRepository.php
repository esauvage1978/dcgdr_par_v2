<?php

namespace App\Repository;

use App\Entity\Cible;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Cible|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cible|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cible[]    findAll()
 * @method Cible[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CibleRepository extends ServiceEntityRepository
{
    const ALIAS='ci';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cible::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS, ActionRepository::ALIAS)
            ->leftJoin(self::ALIAS.'.actions', ActionRepository::ALIAS)
            ->orderBy(self::ALIAS.'.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
