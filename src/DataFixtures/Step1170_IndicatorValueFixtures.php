<?php

namespace App\DataFixtures;

use App\Entity\Deployement;
use App\Entity\Indicator;
use App\Entity\IndicatorValue;
use App\Helper\FixturesImportData;
use App\Manager\IndicatorValueManager;
use App\Repository\DeployementRepository;
use App\Repository\IndicatorRepository;
use App\Validator\IndicatorValueValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class Step1170_IndicatorValueFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'dcgdr_deploiement_indicateur';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;
    /**
     * @var IndicatorValueValidator
     */
    private $validator;

    /**
     * @var IndicatorValueManager
     */
    private $manager;
    /**
     * @var Deployement[]
     */
    private $deployements;

    /**
     * @var Indicator[]
     */
    private $indicators;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    public function __construct(
        FixturesImportData $fixturesImportData,
        IndicatorValueValidator $validator,
        IndicatorValueManager $manager,
        IndicatorRepository $indicatorRepo,
        DeployementRepository $deployementRep,
        EntityManagerInterface $entityManagerI
    ) {
        $this->fixturesImportData = $fixturesImportData;
        $this->validator = $validator;
        $this->manager = $manager;
        $this->indicators = $indicatorRepo->findAll();
        $this->deployements = $deployementRep->findAll();
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
            $instance = $this->initialise(new IndicatorValue(), $data[$i]);

            if (null !== $instance) {
                $this->checkAndPersist($instance);
            }
        }

        $this->entityManagerInterface->flush();
    }

    private function checkAndPersist(IndicatorValue $instance)
    {
        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(IndicatorValue::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManagerInterface->persist($instance);
        } else {
            var_dump('Validator : '.$this->validator->getErrors($instance));
        }
    }

    private function initialise(IndicatorValue $instance, $data): ?IndicatorValue
    {
        /** @var Deployement $deployement */
        $deployement = $this->getInstance($data['id_deploiement'], $this->deployements);
        /** @var Indicator $indicator */
        $indicator = $this->getInstance($data['id_indicateur_maquette'], $this->indicators);

        if (is_a($deployement, Deployement::class)
            &&
            is_a($indicator, Indicator::class)) {
            $instance
                ->setId($data['n0_num'])
                ->setTaux1(null === $data['taux1'] ? '0' : $data['taux1'])
                ->setTaux2(null === $data['taux2'] ? '0' : $data['taux2'])
                ->setDeployement($deployement)
                ->setIndicator($indicator)
                ->setEnable(true)
                ->setContent($data['description']);

            if ('qualitatif' == $indicator->getIndicatorType()) {
                $instance
                    ->setGoal(100)
                    ->setValue(null === $data['taux1'] ? '0' : $data['taux1']);
            } else {
                $instance
                    ->setGoal($this->changeValue($data['objectif']))
                    ->setValue($this->changeValue($data['valeur']));
            }

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
        return ['step1170'];
    }
}
