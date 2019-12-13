<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Helper\FixturesImportData;
use App\Manager\UserManager;
use App\Validator\UserValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class Step1000_UserFixtures extends Fixture implements FixtureGroupInterface
{
    CONST FILENAME = 'dcgdr_utilisateur';
    /**
     * @var FixturesImportData
     */
    private $importData;
    /**
     * @var UserValidator
     */
    private $validator;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    public function __construct(
        FixturesImportData $importData,
        UserValidator $validator,
        UserManager $userManager,
        EntityManagerInterface $entityManagerI
    ) {
        $this->importData = $importData;
        $this->validator = $validator;
        $this->userManager = $userManager;
        $this->entityManagerInterface=$entityManagerI;
    }

    public function load(ObjectManager $manager)
    {
        $data = $this->importData->importToArray(self::FILENAME . ".json");

        for ($i = 0; $i < \count($data); $i++) {

            $instance = $this->initialise(new User(), $data[$i]);

            $this->checkAndPersist($instance);

        }


        $manager->flush();
    }


    private function checkAndPersist(User $instance)
    {
        if ($this->validator->isValid($instance)) {
            $metadata = $this->entityManagerInterface->getClassMetadata(User::class);
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $this->entityManagerInterface->persist($instance);
            return;
        }
        var_dump('Validator : ' . $this->validator->getErrors($instance) . $instance->getName());
    }

    private function initialise(User $instance, $data): User
    {
        $roles = [];
        if ($data['admin']) {
            array_push($roles, 'ROLE_ADMIN');
        }
        if ($data['afficher']) {
            array_push($roles, 'ROLE_USER');
        }
        if ($data['droit1']) {
            array_push($roles, 'ROLE_GESTIONNAIRE');
        }
        if ($data['droit2']) {
            array_push($roles, 'ROLE_GESTIONNAIRE_LOCAL');
        }

        $instance
            ->setId($data['n0_num'])
            ->setName($data['nom'])
            ->setActivate($data['afficher'])
            ->setActivateToken(md5(random_bytes(50)))
            ->setEnable($data['afficher'])
            ->setEmail(
                filter_var($data['mail'], FILTER_VALIDATE_EMAIL) ?
                    $data['mail'] :
                    'achanger@live.fr')
            ->setRoles($roles)
            ->setPlainPassword(($data['mdp'] === 'achanger' || $data['mdp'] === null || $data['mdp'] === '') ?
                'AchangerMerci1' :
                $data['mdp'])
            ->setCreatedAt(new \DateTime());

        $this->userManager->initialise($instance);

        return $instance;
    }

    public static function getGroups(): array
    {
        return ['step1000'];
    }
}
