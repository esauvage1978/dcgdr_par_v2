<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Helper\FixturesImportData;
use App\Repository\ThematiqueRepository;
use App\Validator\CategoryValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class Step1080_CategoryFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'dcgdr_categorie';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;

    /**
     * @var CategoryValidator
     */
    private $validator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    /**
     * @var \App\Entity\Thematique[]
     */
    private $thematiques;

    public function __construct(
        FixturesImportData $fixturesImportData,
        CategoryValidator $validator,
        ThematiqueRepository $thematiqueRepository,
        EntityManagerInterface $entityManagerI
    )
    {
        $this->fixturesImportData = $fixturesImportData;
        $this->validator = $validator;
        $this->thematiques = $thematiqueRepository->findAll();
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
        $data = $this->fixturesImportData->importToArray(self::FILENAME . '.json');

        for ($i = 0; $i < \count($data); ++$i) {
            $instance = $this->initialise(new Category(), $data[$i]);

            $this->checkAndPersist($instance);

        }

        $this->entityManagerInterface->flush();
    }


    private function checkAndPersist(Category $instance)
    {
        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(Category::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManagerInterface->persist($instance);
        }
    }

    private function initialise(Category $instance, $data): Category
    {
        $the = $this->getInstance($data['id_thematique'], $this->thematiques);
        $instance
            ->setId($data['n0_num'])
            ->setName($data['nom'])
            ->setRef($data['code'])
            ->setEnable($data['afficher'])
            ->setContent($data['description'])
            ->setTaux1(null === $data['taux1'] ? '0' : $data['taux1'])
            ->setTaux2(null === $data['taux2'] ? '0' : $data['taux2'])
            ->setThematique($the);

        return $instance;
    }


    public static function getGroups(): array
    {
        return ['step1080'];
    }
}
