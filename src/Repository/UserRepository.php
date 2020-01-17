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
    const ALIAS = 'u';
    const ALIAS_DEPLOYEMENT_WRITERS = 'cdwu';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS, OrganismeRepository::ALIAS, CorbeilleRepository::ALIAS)
            ->leftJoin(self::ALIAS.'.organismes',OrganismeRepository::ALIAS)
            ->leftJoin(self::ALIAS.'.corbeilles',CorbeilleRepository::ALIAS)
            ->orderBy(self::ALIAS . '.name', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllWriterForDeployement()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                CorbeilleRepository::ALIAS,
            )
            ->innerJoin(self::ALIAS.'.corbeilles',CorbeilleRepository::ALIAS)
            ->innerJoin(CorbeilleRepository::ALIAS.'.deployementWriters',DeployementRepository::ALIAS)
            ->where(CorbeilleRepository::ALIAS.'.enable=true')
            ->andWhere(self::ALIAS.'.enable=true')
            ->orderBy(self::ALIAS . '.name', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return User[] Returns an array of User objects
     */
    public function findAllForContact()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->andWhere(self::ALIAS.'.roles like :val1')
            ->orWhere(self::ALIAS.'.roles like :val2')
            ->setParameter('val1', '%ROLE_ADMIN%')
            ->setParameter('val2', '%"ROLE_GESTIONNAIRE"%')
            ->orderBy(self::ALIAS.'.name', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
}
