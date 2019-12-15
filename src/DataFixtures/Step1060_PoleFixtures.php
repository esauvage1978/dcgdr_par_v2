<?php

namespace App\DataFixtures;

use App\Entity\Axe;
use App\Entity\Pole;
use App\Repository\AxeRepository;
use App\Validator\PoleValidator;
use App\Helper\FixturesImportData;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class Step1060_PoleFixtures extends Fixture implements  FixtureGroupInterface
{
    const FILENAME = 'dcgdr_pole';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;

    /**
     * @var PoleValidator
     */
    private $validator;

    /**
     * @var \App\Entity\Axe[]
     */
    private $axes;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    public function __construct(
        FixturesImportData $fixturesImportData,
        PoleValidator $validator,
        AxeRepository $axeRepository,
        EntityManagerInterface $entityManagerI
    ) {
        $this->fixturesImportData = $fixturesImportData;
        $this->validator = $validator;
        $this->axes=$axeRepository->findAll();
        $this->entityManagerInterface=$entityManagerI;

    }

    public function load(ObjectManager $manager)
    {
        $data = $this->fixturesImportData->importToArray(self::FILENAME.'.json');

        for ($i = 0; $i < count($data); ++$i) {
            $instance = $this->initialise(new Pole(), $data[$i]);

            $this->checkAndPersist($instance);

        }

        $this->entityManagerInterface->flush();
    }



    private function checkAndPersist(Pole $instance)
    {
        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(Pole::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManagerInterface->persist($instance);
        } else {
            var_dump('Validator : '.$this->validator->getErrors($instance));
        }
    }

    public function getInstance(string $id, $entitys)
    {
        foreach ($entitys as $entity) {
            if ($entity->getId() == $id) {
                return $entity;
            }
        }
    }

    private function initialise(Pole $instance, $data): Pole
    {
        /** @var Axe $axe */
        $axe = $this->getInstance($data['id_axe'], $this->axes);
        $instance
            ->setId($data['n0_num'])
            ->setName($data['nom'])
            ->setEnable($data['afficher'])
            ->setContent($data['description'])
            ->setTaux1(null === $data['taux1'] ? '0' : $data['taux1'])
            ->setTaux2(null === $data['taux2'] ? '0' : $data['taux2'])
            ->setAxe($axe)
        ;

        return $instance;
    }

    public static function getGroups(): array
    {
        return ['step1060'];
    }


}
