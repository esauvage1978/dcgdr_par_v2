<?php

namespace App\DataFixtures;

use App\Entity\Action;
use App\Entity\Deployement;
use App\Entity\Organisme;
use App\Helper\FixturesImportData;
use App\Repository\ActionRepository;
use App\Repository\CibleRepository;
use App\Repository\OrganismeRepository;
use App\Manager\DeployementManager;
use App\Validator\DeployementValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class Step1150_DeployementFixtures extends Fixture implements  FixtureGroupInterface
{
    const FILENAME = 'dcgdr_deploiement';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;

    /**
     * @var DeployementValidator
     */
    private $validator;

    /**
     * @var DeployementManager
     */
    private $manager;

    /**
     * @var Organisme[]
     */
    private $organismes;

    /**
     * @var Action[]
     */
    private $actions;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    public function __construct(
        FixturesImportData $fixturesImportData,
        DeployementValidator $validator,
        DeployementManager $manager,
        OrganismeRepository $organismeRepository,
        ActionRepository $actionRepository,
        EntityManagerInterface $entityManagerI)
    {
        $this->fixturesImportData = $fixturesImportData;
        $this->validator = $validator;
        $this->manager = $manager;
        $this->organismes = $organismeRepository->findAll();
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
            $instance = $this->initialise(new Deployement(), $data[$i]);

            if (null !== $instance) {
                $this->checkAndPersist($instance);

            }
        }

        $this->entityManagerInterface->flush();
    }


    private function checkAndPersist(Deployement $instance)
    {
        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(Deployement::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManagerInterface->persist($instance);
        } else {
            var_dump('Validator : '.$this->validator->getErrors($instance));
        }
    }

    private function initialise(Deployement $instance, $data): ?Deployement
    {
        $organisme = $this->getInstance($data['id_organisme'], $this->organismes);
        $action = $this->getInstance($data['id_pa_actions'], $this->actions);

        if (is_a($action, Action::class)
            &&
            is_a($organisme, Organisme::class)) {
            $instance
                ->setId($data['n0_num'])
                ->setTaux1(null === $data['taux1'] ? '0' : $data['taux1'])
                ->setTaux2(null === $data['taux2'] ? '0' : $data['taux2'])
                ->setAction($action)
                ->setOrganisme($organisme)
                ->setShowAt($this->convertDate($data['echeance']));

            return $instance;
        }

        return null;
    }

    public function convertDate(?string $date): ?\DateTimeInterface
    {
        if (null === $date) {
            return null;
        }

        return new \DateTime(str_replace('/', '-', $date));
    }


    public static function getGroups(): array
    {
        return ['step1150'];
    }
}
