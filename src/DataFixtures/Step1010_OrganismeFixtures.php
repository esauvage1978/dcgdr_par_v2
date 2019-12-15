<?php

namespace App\DataFixtures;

use App\Entity\Organisme;
use App\Helper\FixturesImportData;
use App\Validator\OrganismeValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class Step1010_OrganismeFixtures extends Fixture implements FixtureGroupInterface
{
    CONST FILENAME = 'dcgdr_organisme';
    /**
     * @var FixturesImportData
     */
    private $importData;
    /**
     * @var OrganismeValidator
     */
    private $validator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    public function __construct(
        FixturesImportData $importData,
        OrganismeValidator $validator,
        EntityManagerInterface $entityManagerI
    )
    {
        $this->importData = $importData;
        $this->validator = $validator;
        $this->entityManagerInterface=$entityManagerI;
    }

    public function load(ObjectManager $manager)
    {

        $data = $this->importData->importToArray(self::FILENAME . ".json");

        for ($i = 0; $i < \count($data); $i++) {

            $instance = $this->initialise(new Organisme(), $data[$i]);

            $this->checkAndPersist($instance);

        }

        $this->entityManagerInterface->flush();
    }


    private function checkAndPersist( Organisme $instance)
    {
        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(Organisme::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManagerInterface->persist($instance);
            return;
        }
        var_dump('Validator : ' . $this->validator->getErrors($instance));
    }

    private function initialise(Organisme $instance, $data): Organisme
    {
        $instance
            ->setId($data['n0_num'])
            ->setName($data['nom'])
            ->setRef($data['code'])
            ->setAlterable($data['administrable'])
            ->setContent($data['description'])
            ->setEnable($data['afficher']);
        return $instance;
    }

    public static function getGroups(): array
    {
        return ['step1010'];
    }

}
