<?php

namespace App\DataFixtures;

use App\Entity\Deployement;
use App\Entity\Indicator;
use App\Entity\IndicatorValue;
use App\Entity\IndicatorValueHistory;
use App\Entity\User;
use App\Helper\FixturesImportData;
use App\Indicator\IndicatorData;
use App\Manager\IndicatorValueHistoryManager;
use App\Manager\IndicatorValueManager;
use App\Repository\DeployementRepository;
use App\Repository\IndicatorRepository;
use App\Repository\IndicatorValueRepository;
use App\Repository\UserRepository;
use App\Validator\IndicatorValueHistoryValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class Step1180_IndicatorValueHistoryFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'dcgdr_dep_ind_saisie';

    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;

    /**
     * @var IndicatorValueHistoryValidator
     */
    private $validator;

    /**
     * @var IndicatorValueHistoryManager
     */
    private $manager;

    /**
     * @var IndicatorValue[]
     */
    private $indicators;

    /**
     * @var User[]
     */
    private $users;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    public function __construct(
        FixturesImportData $fixturesImportData,
        IndicatorValueHistoryValidator $validator,
        IndicatorValueHistoryManager $manager,
        IndicatorValueRepository $indicatorRepo,
        UserRepository $userRepo,
        EntityManagerInterface $entityManagerI
    ) {
        $this->fixturesImportData = $fixturesImportData;
        $this->validator = $validator;
        $this->manager = $manager;
        $this->indicators = $indicatorRepo->findAll();
        $this->users = $userRepo->findAll();
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
            $instance = $this->initialise(new IndicatorValueHistory(), $data[$i]);

            if (null !== $instance) {
                $this->checkAndPersist($instance);
            }
        }

        $this->entityManagerInterface->flush();
    }

    private function checkAndPersist(IndicatorValueHistory $instance)
    {
        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(IndicatorValueHistory::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManagerInterface->persist($instance);
        } else {
            var_dump('Validator : '.$this->validator->getErrors($instance));
        }
    }

    private function initialise(IndicatorValueHistory $instance, $data): ?IndicatorValueHistory
    {
        /** @var IndicatorValue $indicator */
        $indicator = $this->getInstance($data['id_ind'], $this->indicators);

        /** @var User $user */
        $user = $this->getInstance($data['id_u_create'], $this->users);

        if (is_a($user, User::class)
            &&
            is_a($indicator, IndicatorValue::class)) {
            $instance
                ->setId($data['n0_num'])
                ->setTaux1(null === $data['taux1'] ? '0' : $data['taux1'])
                ->setTaux2(null === $data['taux2'] ? '0' : $data['taux2'])
                ->setAddedAt($this->convertDate($data['date_create']))
                ->setGoal($data['objectif'])
                ->setValue($data['valeur'])
                ->setIndicatorValue($indicator)
                ->setUser($user)
                ->setContent($data['description']);

            return $instance;
        }

        return null;
    }

    private function changeValue(?string $value)
    {
        if (null === $value) {
            return 0;
        }
        $value = preg_replace('`[^0-9]`', '', $value);
        if ('' === $value) {
            return 0;
        }

        return $value;
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
        return ['step1180'];
    }
}
