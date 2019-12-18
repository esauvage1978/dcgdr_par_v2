<?php

namespace App\DataFixtures;

use App\Entity\Thematique;
use App\Helper\FixturesImportData;
use App\Repository\PoleRepository;
use App\Validator\ThematiqueValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class Step1070_ThematiqueFixtures extends Fixture implements  FixtureGroupInterface
{
    const FILENAME = 'dcgdr_thematique';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;
    /**
     * @var ThematiqueValidator
     */
    private $validator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    /**
     * @var \App\Entity\Pole[]
     */
    private $poles;

    public function __construct(
        FixturesImportData $fixturesImportData,
        ThematiqueValidator $validator,
        PoleRepository $poleRepository,
        EntityManagerInterface $entityManagerI
    ) {
        $this->fixturesImportData = $fixturesImportData;
        $this->validator = $validator;
        $this->poles=$poleRepository->findAll();
        $this->entityManagerInterface=$entityManagerI;
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
            $instance = $this->initialise(new Thematique(), $data[$i]);

            $this->checkAndPersist( $instance);


        }

        $this->entityManagerInterface->flush();
    }



    private function checkAndPersist(Thematique $instance)
    {
        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(Thematique::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManagerInterface->persist($instance);
        } else {
            var_dump('Validator : '.$this->validator->getErrors($instance));
        }
    }

    private function initialise(Thematique $instance, $data): Thematique
    {
        $pole = $this->getInstance($data['id_pole'], $this->poles);

        $instance
            ->setId($data['n0_num'])
            ->setName($data['nom'])
            ->setRef($data['code'])
            ->setEnable($data['afficher'])
            ->setContent($data['description'])
            ->setTaux1(null === $data['taux1'] ? '0' : $data['taux1'])
            ->setTaux2(null === $data['taux2'] ? '0' : $data['taux2'])
            ->setPole($pole);

        return $instance;
    }



    public static function getGroups(): array
    {
        return ['step1070'];
    }
}
