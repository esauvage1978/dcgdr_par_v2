<?php

namespace App\DataFixtures;

use App\Entity\Action;
use App\Entity\Corbeille;
use App\Helper\FixturesImportData;
use App\Repository\ActionRepository;
use App\Repository\CorbeilleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class Step1140_ActionsWriterCorbeilleFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'dcgdr_rel_pa_actions_corbeilles_modification';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;

    /**
     * @var Corbeille[]
     */
    private $corbeilles;

    /**
     * @var Action[]
     */
    private $actions;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    /**
     * @var Corbeille|null
     */
    private $writers;

    /**
     * @var Corbeille|null
     */
    private $validers;

    public function __construct(
        FixturesImportData $fixturesImportData,
        CorbeilleRepository $corbeilleRepository,
        ActionRepository $actionRepository,
        EntityManagerInterface $entityManagerI
    ) {
        $this->fixturesImportData = $fixturesImportData;
        $this->corbeilles = $corbeilleRepository->findAll();
        $this->actions = $actionRepository->findAll();
        $this->entityManagerInterface = $entityManagerI;
        $this->writers = $corbeilleRepository->findOneBy(['id' => '32']);
        $this->validers = $corbeilleRepository->findOneBy(['id' => '7']);
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
            $corbeille = $this->getInstance($data[$i]['droite'], $this->corbeilles);
            /** @var Action $action */
            $action = $this->getInstance($data[$i]['gauche'], $this->actions);

            if (is_a($corbeille, Corbeille::class)
                &&
                is_a($action, Action::class)
            ) {
                $action->addWriter($corbeille);

                $this->entityManagerInterface->persist($action);
            }

            foreach ($this->actions as $action) {
                $action->addWriter($this->writers)
                    ->addValider($this->validers);

                $manager->persist($action);
            }
        }

        $manager->flush();


    }

    public static function getGroups(): array
    {
        return ['step1140'];
    }
}
