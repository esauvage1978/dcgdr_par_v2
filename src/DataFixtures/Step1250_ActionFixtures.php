<?php

namespace App\DataFixtures;

use App\Entity\Action;
use App\Helper\FixturesImportData;
use App\Repository\ActionRepository;
use App\Manager\ActionManager;
use App\Workflow\WorkflowActionManager;
use App\Validator\ActionValidator;
use App\Workflow\WorkflowData;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class Step1250_ActionFixtures extends Fixture implements FixtureGroupInterface
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
     * @var WorkflowActionManager
     */
    private $workflowManager;
    private $actions;
    private $actionManager;

    public function __construct(
        FixturesImportData $fixturesImportData,
        ActionValidator $validator,
        WorkflowActionManager $workflowManager,
        ActionRepository $actionRepository,
                                ActionManager $actionManager
    ) {
        $this->fixturesImportData = $fixturesImportData;
        $this->validator = $validator;
        $this->workflowManager = $workflowManager;
        $this->actions = $actionRepository->findAll();
        $this->actionManager = $actionManager;
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
            $instance = $this->initialise(new Action(), $data[$i], $manager);
        }
    }

    private function initialise(Action $instance, $data, ObjectManager $manager): ?Action
    {
        $instance = $this->getInstance($data['n0_num'], $this->actions);
        if (null == $instance) {
            dump($data['n0_num']);
        } else {
            $content='bascule automatique du dossier (' . $data['id_etat'] . ')';
            switch ($data['id_etat']) {
                case "1":
                    $this->workflowManager->applyTransition($instance, WorkflowData::TRANSITION_TO_COTECH,$content,true);
                    break;
                case "2":
                    $this->workflowManager->applyTransition($instance, WorkflowData::TRANSITION_TO_COTECH,'',true);
                    $this->workflowManager->applyTransition($instance, WorkflowData::TRANSITION_TO_REJECTED,$content,true);
                    break;
                case "3":
                    $this->workflowManager->applyTransition($instance, WorkflowData::TRANSITION_TO_COTECH,'',true);
                    $this->workflowManager->applyTransition($instance, WorkflowData::TRANSITION_TO_CODIR,'',true);
                    $this->workflowManager->applyTransition($instance, WorkflowData::TRANSITION_TO_FINALISED,$content,true);
                    break;
                case "4":
                    $this->workflowManager->applyTransition($instance, WorkflowData::TRANSITION_TO_COTECH,'',true);
                    $this->workflowManager->applyTransition($instance, WorkflowData::TRANSITION_TO_CODIR,'',true);
                    $this->workflowManager->applyTransition($instance, WorkflowData::TRANSITION_TO_FINALISED,'',true);
                    $this->workflowManager->applyTransition($instance, WorkflowData::TRANSITION_TO_DEPLOYE,$content,true);
                    break;
                case "5":
                    $this->workflowManager->applyTransition($instance, WorkflowData::TRANSITION_TO_COTECH,'',true);
                    $this->workflowManager->applyTransition($instance, WorkflowData::TRANSITION_TO_CODIR,'',true);
                    $this->workflowManager->applyTransition($instance, WorkflowData::TRANSITION_TO_FINALISED,'',true);
                    $this->workflowManager->applyTransition($instance, WorkflowData::TRANSITION_TO_DEPLOYE,'',true);
                    $this->workflowManager->applyTransition($instance, WorkflowData::TRANSITION_TO_MEASURED,$content,true);
                    break;
                case "6":
                    $this->workflowManager->applyTransition($instance, WorkflowData::TRANSITION_TO_COTECH,'',true);
                    $this->workflowManager->applyTransition($instance, WorkflowData::TRANSITION_TO_CODIR,'',true);
                    $this->workflowManager->applyTransition($instance, WorkflowData::TRANSITION_TO_FINALISED,'',true);
                    $this->workflowManager->applyTransition($instance, WorkflowData::TRANSITION_TO_DEPLOYE,'',true);
                    $this->workflowManager->applyTransition($instance, WorkflowData::TRANSITION_TO_MEASURED,$content,true);
                    break;
                case "7":
                    $this->workflowManager->applyTransition($instance, WorkflowData::TRANSITION_TO_COTECH,'',true);
                   $this->workflowManager->applyTransition($instance, WorkflowData::TRANSITION_TO_CODIR,'',true);
                    $this->workflowManager->applyTransition($instance, WorkflowData::TRANSITION_TO_FINALISED,'',true);
                    $this->workflowManager->applyTransition($instance, WorkflowData::TRANSITION_TO_DEPLOYE,'',true);
                    $this->workflowManager->applyTransition($instance, WorkflowData::TRANSITION_TO_MEASURED,$content,true);
                    break;
                case "8":
                    $this->workflowManager->applyTransition($instance, WorkflowData::TRANSITION_TO_ABANDONNED,$content,true);
                    break;

            }

        }

        return $instance;
    }

    public function convertDate(?string $date): ?\DateTimeInterface
    {
        if (null === $date) {
            return null;
        }

        return new DateTime(str_replace('/', '-', $date));
    }

    public static function getGroups(): array
    {
        return ['step1250'];
    }
}
