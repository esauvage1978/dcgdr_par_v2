<?php

namespace App\DataFixtures;

use App\Entity\Action;
use App\Entity\Indicator;
use App\Helper\FixturesImportData;
use App\Indicator\IndicatorData;
use App\Manager\IndicatorManager;
use App\Repository\ActionRepository;
use App\Validator\IndicatorValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class Step1160_IndicatorFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'dcgdr_deploiement_indicateur_maquette';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;
    /**
     * @var IndicatorValidator
     */
    private $validator;

    /**
     * @var IndicatorManager
     */
    private $manager;

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
        IndicatorValidator $validator,
        IndicatorManager $manager,
        ActionRepository $actionRepository,
        EntityManagerInterface $entityManagerI
    ) {
        $this->fixturesImportData = $fixturesImportData;
        $this->validator = $validator;
        $this->manager = $manager;
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
            $instance = $this->initialise(new Indicator(), $data[$i]);

            if (null !== $instance) {
                $this->checkAndPersist($instance);
            }
        }

        $this->entityManagerInterface->flush();
    }

    private function checkAndPersist(Indicator $instance)
    {
        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(Indicator::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManagerInterface->persist($instance);
        } else {
            var_dump('Validator : '.$this->validator->getErrors($instance));
        }
    }

    private function initialise(Indicator $instance, $data): ?Indicator
    {
        $action = $this->getInstance($data['id_pa_actions'], $this->actions);

        if (is_a($action, Action::class)
           ) {
            $instance
                ->setId($data['n0_num'])
                ->setTaux1(null === $data['taux1'] ? '0' : $data['taux1'])
                ->setTaux2(null === $data['taux2'] ? '0' : $data['taux2'])
                ->setGoal(null === $data['objectif'] ? '0' : $data['objectif'])
                ->setValue(null === $data['valeur'] ? '0' : $data['valeur'])
                ->setName($data['nom'])
                ->setContent($data['description'])
                ->setEnable($data['afficher'])
                ->setIndicatortype('1' == $data['type_indicateur'] ?
                    IndicatorData::QUALITATIF :
                    IndicatorData::QUANTITATIF)
                ->setAction($action)

                ;

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
        return ['step1160'];
    }
}
