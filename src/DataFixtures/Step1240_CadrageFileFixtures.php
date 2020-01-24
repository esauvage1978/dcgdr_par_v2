<?php

namespace App\DataFixtures;

use App\Entity\Action;
use App\Entity\CadrageFile;
use App\Helper\FileDirectory;
use App\Repository\ActionRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Finder;

class Step1240_CadrageFileFixtures extends Fixture implements FixtureGroupInterface
{
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
        ActionRepository $actionRepository,
        EntityManagerInterface $entityManagerI,
        ParameterBagInterface $params
    ) {
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
        $finder = new Finder();
        $finder->files()->in($this->params->get('directory_data_doc').'/pa_cadrage');
        foreach ($finder as $file) {
            if ($file->isFile()) {

                $actionId = substr(
                    $file->getPath(),
                    strlen($this->params->get('directory_data_doc').'/pa_cadrage') + 1,
                    strlen($file->getPath()) - strlen($this->params->get('directory_data_doc').'/pa_cadrage')
                );

                $extension = $file->getExtension();

                $filename = substr(
                        $file->getFilename(),
                    0,
                    strlen($file->getFilename()) -
                    strlen($extension) - 1);

                $instance = $this->initialise(
                    new CadrageFile(),
                    $actionId,
                    $extension,
                    $filename,
                    $file->getPath());

                if(!empty( $instance)) {
                    $this->checkAndPersist($instance);
                }
            }
        }

        $this->entityManagerInterface->flush();
    }

    private function checkAndPersist(CadrageFile $instance)
    {
        $this->entityManagerInterface->persist($instance);
    }

    private function initialise(CadrageFile $instance, $actionid, $extension, $filename, $dirSource): ?CadrageFile
    {
        /** @var Action $action */
        $action = $this->getInstance($actionid, $this->actions);

        if (is_a($action, Action::class)
           ) {

            if(strlen($filename)>50) {
                return null;
            }

            $instance
                ->setTitle($filename)
                ->setFileExtension($extension)
                ->setFileName($filename)
                ->setAction($action)
                ;

            $this->moveFile($dirSource, $actionid, $filename.'.' . $extension);

            return $instance;
        }

        return null;
    }

    private function moveFile(string $dirSource, string $actionId, string $fileName)
    {
        $dirDestination = $this->params->get('directory_file_action');

        $this->fileDirectory->createDir($dirDestination, $actionId);

        $dirDestination=$dirDestination.'/'. $actionId;
        $this->fileDirectory->createDir($dirDestination, 'cadrage');

        $dirDestination=$dirDestination.'/'. $actionId .'/cadrage';

        $this->fileDirectory->moveFile($dirSource, $fileName, $dirDestination, $fileName);
    }

    public static function getGroups(): array
    {
        return ['step1240'];
    }
}
