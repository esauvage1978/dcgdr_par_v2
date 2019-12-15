<?php

namespace App\Repository;

use App\Entity\Pole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;

/**
 * @method Pole|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pole|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pole[]    findAll()
 * @method Pole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PoleRepository extends ServiceEntityRepository
{
    const POLE='p';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pole::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::POLE)
            ->select(self::POLE, AxeRepository::AXE)
            ->leftJoin(self::POLE.'.axe', AxeRepository::AXE)
            ->orderBy(self::POLE.'.name', 'ASC')
            ->orderBy(AxeRepository::AXE.'.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

}
