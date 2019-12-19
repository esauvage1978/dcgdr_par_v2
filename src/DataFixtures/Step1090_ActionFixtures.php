<?php

namespace App\DataFixtures;

use App\Entity\Action;
use App\Entity\Category;
use App\Helper\FixturesImportData;
use App\Repository\CategoryRepository;
use App\Validator\ActionValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class Step1090_ActionFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'dcgdr_pa_actions';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;
    /**
     * @var ActionValidator
     */
    private $validator;

    /**
     * @var Category[]
     */
    private $categories;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    public function __construct(
        FixturesImportData $fixturesImportData,
        ActionValidator $validator,
        CategoryRepository $categoryRepository,
        EntityManagerInterface $entityManagerI
    )
    {
        $this->fixturesImportData = $fixturesImportData;
        $this->validator = $validator;
        $this->categories = $categoryRepository->findAll();
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
            $instance = $this->initialise(new Action(), $data[$i]);

            $this->checkAndPersist($instance);

        }

        $this->entityManagerInterface->flush();
    }


    private function checkAndPersist(Action $instance)
    {
        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(Action::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManagerInterface->persist($instance);
            return;
        }
        var_dump('Validator : ' . $this->validator->getErrors($instance));
    }

    private function initialise(Action $instance, $data): Action
    {
        $category = $this->getInstance($data['id_categorie'], $this->categories);
        if (!is_a($category, Category::class)) {
            $category = $this->getInstance('8', $this->categories);
        }

        $instance
            ->setId($data['n0_num'])
            ->setName($data['nom'])
            ->setRef(null === $data['num_action'] ? '00' : $data['num_action'])
            ->setEnable($data['afficher'])
            ->setExperimental($data['experimentation'])
            ->setContent($data['description'])
            ->setCadrage($data['cadrage'])
            ->setTaux1(null === $data['taux1'] ? '0' : $data['taux1'])
            ->setTaux2(null === $data['taux2'] ? '0' : $data['taux2'])
            ->setCategory($category)
            ->setRegionStartAt($this->convertDate($data['date_region_debut']))
            ->setRegionEndAt($this->convertDate($data['date_region_fin']))
            ->setCadrage('Rédaction en cours');

        if ($data['id_etat'] == '4' && $data['date_region_fin'] === null) {
            $instance->setRegionEndAt($this->convertDate("31/12/2020"));
        }


        return $instance;
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
        return ['step1090'];
    }
}
