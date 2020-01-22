<?php

namespace App\DataFixtures;

use App\Entity\Action;
use App\Entity\ActionLink;
use App\Entity\Indicator;
use App\Helper\FixturesImportData;
use App\Repository\ActionRepository;
use App\Validator\IndicatorValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class Step1200_ActionLinkFixtures extends Fixture implements FixtureGroupInterface
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

    public function __construct(
        FixturesImportData $fixturesImportData,
        IndicatorValidator $validator,
        ActionRepository $actionRepository,
        EntityManagerInterface $entityManagerI
    ) {
        $this->fixturesImportData = $fixturesImportData;
        $this->validator = $validator;
        $this->actions = $actionRepository->findAll();
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
        $data = $this->fixturesImportData->importToArray(self::FILENAME.'.json');

        for ($i = 0; $i < \count($data); ++$i) {
            $instance = $this->initialise(new ActionLink(), $data[$i]);

            if (null !== $instance) {
                $this->checkAndPersist($instance);
            }
        }

        $this->entityManagerInterface->flush();
    }

    private function checkAndPersist(ActionLink $instance)
    {
        $this->entityManagerInterface->persist($instance);
    }

    private function initialise(ActionLink $instance, $data): ?ActionLink
    {
        if ('pa_actions' != $data['domaine'] ||
            '0' == $data['afficher'] ||
            '0' == $data['lien']) {
            return null;
        }
        $action = $this->getInstance($data['obj_num'], $this->actions);

        if (is_a($action, Action::class)
           ) {
            $instance
                ->setTitle($data['titre'])
                ->setLink($data['adresse'])
                ->setAction($action)
                ;

            return $instance;
        }

        return null;
    }

    public static function getGroups(): array
    {
        return ['step1200'];
    }
}
