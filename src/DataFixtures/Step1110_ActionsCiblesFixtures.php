<?php

namespace App\DataFixtures;

use App\Entity\Cible;
use App\Entity\Action;
use App\Helper\FixturesImportData;
use App\Repository\ActionRepository;
use App\Repository\CibleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class Step1110_ActionsCiblesFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'dcgdr_rel_pa_actions_cible';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;
    private $cibles;
    private $actions;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    public function __construct(
        FixturesImportData $fixturesImportData,
        CibleRepository $cibleRepository,
        ActionRepository $actionRepository,
        EntityManagerInterface $entityManagerI
    ) {
        $this->fixturesImportData = $fixturesImportData;
        $this->cibles = $cibleRepository->findAll();
        $this->actions = $actionRepository->findAll();
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
            /** @var Cible $cible */
            $cible = $this->getInstance($data[$i]['droite'], $this->cibles);
            $action = $this->getInstance($data[$i]['gauche'], $this->actions);

            if (is_a($cible, Cible::class)
                &&
                is_a($action, Action::class)
            ) {
                $cible->addAction($action);

                $this->entityManagerInterface->persist($cible);
            }
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['step1110'];
    }
}
