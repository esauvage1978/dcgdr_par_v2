<?php

namespace App\DataFixtures;

use App\Entity\Action;
use App\Entity\ActionFile;
use App\Helper\FileDirectory;
use App\Helper\FixturesImportData;
use App\Repository\ActionRepository;
use App\Validator\IndicatorValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Step1210_ActionFileFixtures extends Fixture implements FixtureGroupInterface
{
    const FILENAME = 'dcgdr_mb_pj_action_link';
    /**
     * @var FixturesImportData
     */
    private $fixturesImportData;
    /**
     * @var IndicatorValidator
     */
    private $validator;

    /**
     * @var Action[]
     */
    private $actions;

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
        IndicatorValidator $validator,
        ActionRepository $actionRepository,
        EntityManagerInterface $entityManagerI,
        ParameterBagInterface $params
    ) {
        $this->fixturesImportData = $fixturesImportData;
        $this->validator = $validator;
        $this->actions = $actionRepository->findAll();
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
            $instance = $this->initialise(new ActionFile(), $data[$i]);

            if (null !== $instance) {
                $this->checkAndPersist($instance);
            }
        }

        $this->entityManagerInterface->flush();
    }

    private function checkAndPersist(ActionFile $instance)
    {
        $this->entityManagerInterface->persist($instance);
    }

    private function initialise(ActionFile $instance, $data): ?ActionFile
    {
        if ('pa_actions' != $data['domaine'] ||
            '0' == $data['afficher'] ||
            '1' == $data['lien']) {
            return null;
        }

        /** @var Action $action */
        $action = $this->getInstance($data['obj_num'], $this->actions);

        if (is_a($action, Action::class)
           ) {
            $instance
                ->setTitle($data['titre'])
                ->setFileExtension($data['extension'])
                ->setFileName(substr($data['adresse'], 0, strlen($data['adresse']) - 1 - strlen($data['extension'])))
                ->setAction($action)
                ;

            $this->moveFile($action->getId(), $data['adresse']);

            return $instance;
        }

        return null;
    }

    private function moveFile(string $actionId, string $fileName)
    {
        $dirDestination=$this->params->get('directory_file_action');
        $dirSource=$this->params->get('directory_data_doc') . '/pa_actions';

        $this->fileDirectory->createDir($dirDestination,$actionId);

        $this->fileDirectory->moveFile($dirSource, $fileName, $dirDestination .'/' . $actionId, $fileName);

    }

    public static function getGroups(): array
    {
        return ['step1210'];
    }
}
