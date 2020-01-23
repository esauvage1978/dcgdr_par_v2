<?php

namespace App\DataFixtures;

use App\Entity\Action;
use App\Entity\ActionFile;
use App\Entity\Deployement;
use App\Entity\DeployementFile;
use App\Helper\FileDirectory;
use App\Helper\FixturesImportData;
use App\Repository\ActionRepository;
use App\Repository\DeployementRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Step1230_DeployementFileFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'dcgdr_mb_pj_action_link';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;

    /**
     * @var Deployement[]
     */
    private $deployements;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    /**
     * @var FileDirectory
     */
    private $fileDirectory;

    /**
     * @var ParameterBagInterface
     */
    private $params;

    public function __construct(
        FixturesImportData $fixturesImportData,
        DeployementRepository $deployementRepository,
        EntityManagerInterface $entityManagerI,
        ParameterBagInterface $params
    ) {
        $this->fixturesImportData = $fixturesImportData;
        $this->deployements = $deployementRepository->findAll();
        $this->entityManagerInterface = $entityManagerI;
        $this->fileDirectory = new FileDirectory();
        $this->params = $params;
    }

    public function getInstance(string $id, $entitys)
    {
        foreach ($entitys as $entity) {
            if ($entity->getId() == $id) {
                return $entity;
            }
        }

        return null;
    }

    public function load(ObjectManager $manager)
    {
        $data = $this->fixturesImportData->importToArray(self::FILENAME.'.json');

        for ($i = 0; $i < \count($data); ++$i) {
            $instance = $this->initialise(new DeployementFile(), $data[$i]);

            if (null !== $instance) {
                $this->checkAndPersist($instance);
            }
        }

        $this->entityManagerInterface->flush();
    }

    private function checkAndPersist(DeployementFile $instance)
    {
        $this->entityManagerInterface->persist($instance);
    }

    private function initialise(DeployementFile $instance, $data): ?DeployementFile
    {
        if ('deploiement' != $data['domaine'] ||
            '0' == $data['afficher'] ||
            '1' == $data['lien']) {
            return null;
        }

        /** @var Deployement $dep */
        $dep = $this->getInstance($data['obj_num'], $this->deployements);

        if (is_a($dep, Deployement::class)
           ) {
            $instance
                ->setTitle($data['titre'])
                ->setFileExtension($data['extension'])
                ->setFileName(substr($data['adresse'], 0, strlen($data['adresse']) - 1 - strlen($data['extension'])))
                ->setDeployement($dep)
                ;

            $this->moveFile($dep->getId(),$dep->getAction()->getId(), $data['adresse']);

            return $instance;
        }

        return null;
    }

    private function moveFile(string $depId,string $actionId, string $fileName)
    {
        $dirDestination = $this->params->get('directory_file_action');
        $dirSource = $this->params->get('directory_data_doc').'/deploiement';

        $this->fileDirectory->createDir($dirDestination, $actionId);

        $dirDestination = $dirDestination . '/'. $actionId;

        $this->fileDirectory->createDir($dirDestination, $depId);

        $dirDestination = $dirDestination .'/' . $depId;

        $this->fileDirectory->moveFile($dirSource, $fileName, $dirDestination, $fileName);
    }

    public static function getGroups(): array
    {
        return ['step1230'];
    }
}
