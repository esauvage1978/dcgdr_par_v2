<?php

namespace App\DataFixtures;

use App\Entity\Action;
use App\Entity\ActionLink;
use App\Entity\Deployement;
use App\Entity\DeployementLink;
use App\Entity\Indicator;
use App\Helper\FixturesImportData;
use App\Repository\ActionRepository;
use App\Repository\DeployementRepository;
use App\Validator\IndicatorValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class Step1220_DeployementLinkFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'dcgdr_mb_pj_action_link';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;

    /**
     * @var Deployement[]
     */
    private $deployements;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    public function __construct(
        FixturesImportData $fixturesImportData,
        DeployementRepository $deployement,
        EntityManagerInterface $entityManagerI
    ) {
        $this->fixturesImportData = $fixturesImportData;
        $this->deployements = $deployement->findAll();
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
            $instance = $this->initialise(new DeployementLink(), $data[$i]);

            if (null !== $instance) {
                $this->checkAndPersist($instance);
            }
        }

        $this->entityManagerInterface->flush();
    }

    private function checkAndPersist(DeployementLink $instance)
    {
        $this->entityManagerInterface->persist($instance);
    }

    private function initialise(DeployementLink $instance, $data): ?DeployementLink
    {
        if ('deploiement' != $data['domaine'] ||
            '0' == $data['afficher'] ||
            '0' == $data['lien']) {
            return null;
        }
        $action = $this->getInstance($data['obj_num'], $this->deployements);

        if (is_a($action, Deployement::class)
           ) {
            $instance
                ->setTitle($data['titre'])
                ->setLink($data['adresse'])
                ->setDeployement($action)
                ;

            return $instance;
        }

        return null;
    }

    public static function getGroups(): array
    {
        return ['step1220'];
    }
}
