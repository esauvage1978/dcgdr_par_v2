<?php

namespace App\DataFixtures;

use App\Entity\Cible;
use App\Helper\FixturesImportData;
use App\Validator\CibleValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class Step1100_CibleFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'dcgdr_cible';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;
    /**
     * @var CibleValidator
     */
    private $validator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    public function __construct(
        FixturesImportData $fixturesImportData,
        CibleValidator $validator,
        EntityManagerInterface $entityManagerI
    )
    {
        $this->fixturesImportData = $fixturesImportData;
        $this->validator = $validator;
        $this->entityManagerInterface = $entityManagerI;
    }

    public function load(ObjectManager $manager)
    {
        $data = $this->fixturesImportData->importToArray(self::FILENAME . '.json');

        for ($i = 0; $i < \count($data); ++$i) {
            $instance = $this->initialise(new Cible(), $data[$i]);

            $this->checkAndPersist($instance);
        }

        $this->entityManagerInterface->flush();
    }


    private function checkAndPersist(Cible $instance)
    {
        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(Cible::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManagerInterface->persist($instance);
            return;
        }
        var_dump('Validator : ' . $this->validator->getErrors($instance));
    }

    private function initialise(Cible $instance, $data): Cible
    {
        $instance
            ->setId($data['n0_num'])
            ->setName($data['nom'])
            ->setEnable($data['afficher']);

        return $instance;
    }

    public static function getGroups(): array
    {
        return ['step1100'];
    }
}
