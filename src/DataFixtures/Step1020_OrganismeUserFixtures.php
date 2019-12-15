<?php

namespace App\DataFixtures;

use App\Entity\Organisme;
use App\Entity\User;
use App\Helper\FixturesImportData;
use App\Repository\OrganismeRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class Step1020_OrganismeUserFixtures extends Fixture implements  FixtureGroupInterface
{
    CONST FILENAME='dcgdr_rel_organisme_utilisateur';
    /**
     * @var FixturesImportData
     */
    private $importData;

    /**
     * @var Organisme[]
     */
    private $organismes;

    /**
     * @var User[]
     */
    private $users;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    public function __construct(
        FixturesImportData $importData,
        OrganismeRepository $organismeRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManagerI
    ) {
        $this->importData=$importData;
        $this->organismes=$organismeRepository->findAll();
        $this->users=$userRepository->findAll();
        $this->entityManagerInterface=$entityManagerI;
    }

    public function load(ObjectManager $manager)
    {

        $data=$this->importData->importToArray(self::FILENAME. ".json");

        for($i=0;$i<\count($data);$i++) {

            $organisme = $this->getInstance($data[$i]['gauche'], $this->organismes);

            $user = $this->getInstance($data[$i]['droite'], $this->users);

            if( is_a($organisme,Organisme::class)
                &&
                is_a($user,User::class)
            ) {
                $organisme->addUser($user);

                $this->entityManagerInterface->persist($organisme);
            }
        }

        $this->entityManagerInterface->flush();
    }

    public function getInstance(string $id, $entitys)
    {
        foreach ($entitys as $entity) {
            if ($entity->getId() == $id) {
                return $entity;
            }
        }
    }

    public static function getGroups(): array
    {
        return ['step1020'];
    }

}
