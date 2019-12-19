<?php

namespace App\DataFixtures;

use App\Entity\Vecteur;
use App\Entity\Action;
use App\Helper\FixturesImportData;
use App\Repository\ActionRepository;
use App\Repository\CibleRepository;
use App\Repository\VecteurRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;

class Step1130_ActionsVecteursFixtures extends Fixture implements  FixtureGroupInterface
{
    const FILENAME = 'dcgdr_rel_pa_actions_vecteur';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;
    private $vecteurs;
    private $actions;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    public function __construct(
        FixturesImportData $fixturesImportData,
        VecteurRepository $vecteurRepository,
        ActionRepository $actionRepository,
        EntityManagerInterface $entityManagerI
    ) {
        $this->fixturesImportData = $fixturesImportData;
        $this->vecteurs = $vecteurRepository->findAll();
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

            $vecteur = $this->getInstance($data[$i]['droite'], $this->vecteurs);
            /** @var Action $action */
            $action = $this->getInstance($data[$i]['gauche'], $this->actions);


            if (is_a($vecteur, Vecteur::class)
                &&
                is_a($action, Action::class)
            ) {
                $action->addVecteur($vecteur);

                $this->entityManagerInterface->persist($action);
            }
        }

        $this->entityManagerInterface->flush();
    }



    public static function getGroups(): array
    {
        return ['step1130'];
    }
}
