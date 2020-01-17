<?php

namespace App\DataFixtures;

use App\Entity\Corbeille;
use App\Entity\Deployement;
use App\Helper\FixturesImportData;
use App\Repository\CorbeilleRepository;
use App\Repository\DeployementRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class Step1190_DeployementWriterCorbeilleFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'dcgdr_rel_deploiement_corbeilles_modification';

    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;

    /** @var Deployement[] $deployements */
    private $deployements;

    /** @var Corbeille[] $corbeilles */
    private $corbeilles;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    public function __construct(
        FixturesImportData $fixturesImportData,
        CorbeilleRepository $corbeilleRepository,
        DeployementRepository $deployementRepository,
        EntityManagerInterface $entityManagerI
    ) {
        $this->fixturesImportData = $fixturesImportData;
        $this->corbeilles = $corbeilleRepository->findAll();
        $this->deployements = $deployementRepository->findAll();
        $this->entityManagerInterface = $entityManagerI;
    }

    public function getInstance(string $id, $entitys)
    {
        foreach ($entitys as $entity) {
            if ($entity->getId() == $id) {
                return $entity;
            }
        }
    }

    public function load(ObjectManager $manager)
    {
        $data = $this->fixturesImportData->importToArray(self::FILENAME.'.json');

        for ($i = 0; $i < \count($data); ++$i) {
            /** @var Corbeille $corbeille */
            $corbeille = $this->getInstance($data[$i]['droite'], $this->corbeilles);

            /** @var Deployement $gauche */
            $gauche = $this->getInstance($data[$i]['gauche'], $this->deployements);

            if (is_a($corbeille, Corbeille::class)
                &&
                is_a($gauche, Deployement::class)
            ) {
                $gauche->addWriter($corbeille);

                $this->entityManagerInterface->persist($gauche);
            }
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['step1190'];
    }
}
