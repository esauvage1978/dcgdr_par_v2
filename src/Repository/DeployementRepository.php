<?php

namespace App\Repository;

use App\Entity\Deployement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Deployement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Deployement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Deployement[]    findAll()
 * @method Deployement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeployementRepository extends ServiceEntityRepository
{
    const ALIAS = 'd';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Deployement::class);
    }

    public function findAllForAction(string $actionId)
    {
        $builder = $this->createQueryBuilder(self::ALIAS)
            ->select(self::ALIAS,
                ActionRepository::ALIAS)
            ->join(self::ALIAS.'.action', ActionRepository::ALIAS)
            ->join(self::ALIAS.'.organisme', OrganismeRepository::ALIAS)
            ->andWhere(ActionRepository::ALIAS.'.id = :val1')
            ->setParameter('val1', $actionId);

        $builder = $builder
            ->orderBy(OrganismeRepository::ALIAS.'.name', 'ASC');

        return $builder
            ->getQuery()
            ->getResult();
    }
}
