<?php

namespace App\DataFixtures;

use App\Entity\Vecteur;
use App\Validator\VecteurValidator;
use App\Helper\FixturesImportData;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class Step1120_VecteurFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'dcgdr_vecteur';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;
    /**
     * @var VecteurValidator
     */
    private $validator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    public function __construct(
        FixturesImportData $fixturesImportData,
        VecteurValidator $validator,
        EntityManagerInterface $entityManagerI
    ) {
        $this->fixturesImportData = $fixturesImportData;
        $this->validator = $validator;
        $this->entityManagerInterface = $entityManagerI;
    }

    public function load(ObjectManager $manager)
    {
        $data = $this->fixturesImportData->importToArray(self::FILENAME.'.json');

        for ($i = 0; $i < \count($data); ++$i) {
            $instance = $this->initialise(new Vecteur(), $data[$i]);

            $this->checkAndPersist( $instance);

        }

        $this->entityManagerInterface->flush();
    }


    private function checkAndPersist( Vecteur $instance)
    {
        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(Vecteur::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManagerInterface->persist($instance);
        } else {
            var_dump('Validator : '.$this->validator->getErrors($instance));
        }
    }

    private function initialise(Vecteur $instance, $data): Vecteur
    {
        $instance
            ->setId($data['n0_num'])
            ->setName($data['nom'])
            ->setEnable($data['afficher'])
        ;

        return $instance;
    }

    public static function getGroups(): array
    {
        return ['step1120'];
    }
}
