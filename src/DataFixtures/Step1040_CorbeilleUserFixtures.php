<?php

namespace App\DataFixtures;

use App\Entity\Corbeille;
use App\Entity\User;
use App\Helper\FixturesImportData;
use App\Repository\CorbeilleRepository;
use App\Repository\OrganismeRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Helper\ToolCollecion;
use Doctrine\ORM\EntityManagerInterface;

class Step1040_CorbeilleUserFixtures extends Fixture implements  FixtureGroupInterface
{
    CONST FILENAME='dcgdr_rel_corbeille_utilisateur';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;

    /**
     * @var Corbeille[]
     */
    private $corbeilles;

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
        CorbeilleRepository $corbeilleRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManagerI
    ) {
        $this->fixturesImportData = $fixturesImportData;
        $this->corbeilles = $corbeilleRepository->findAll();
        $this->users = $userRepository->findAll();
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

        $data=$this->fixturesImportData->importToArray(self::FILENAME. ".json");

        for($i=0;$i<\count($data);$i++) {

            $corbeille = $this->getInstance($data[$i]['gauche'], $this->corbeilles);

            $user = $this->getInstance($data[$i]['droite'], $this->users);

            if( is_a($corbeille,Corbeille::class)
                &&
                is_a($user,User::class)
            ) {

                $corbeille->addUser($user);

                $this->entityManagerInterface->persist($corbeille);
            }
        }

        $this->entityManagerInterface->flush();
    }


    public static function getGroups(): array
    {
        return ['step1040'];
    }

}
