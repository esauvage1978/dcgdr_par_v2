<?php

namespace App\Subscriber;

use App\Entity\Action;
use App\Twig\SumEnableExtension;
use App\Workflow\WorkflowActionTransitionManager;
use App\Workflow\WorkflowData;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\EnteredEvent;
use Symfony\Component\Workflow\Event\GuardEvent;
use Symfony\Component\Workflow\Event\LeaveEvent;

/**
 * Class ActionSubscriber.
 */
class ActionSubscriber implements EventSubscriberInterface
{


    /**
     * @var SumEnableExtension
     */
    private $sumEnableExtension;

    public function __construct( SumEnableExtension $sumEnableExtension)
    {
        $this->sumEnableExtension = $sumEnableExtension;
    }

    /**
     * @param GuardEvent $event
     */
    public function onGuardToCotech(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_TO_COTECH);
    }
    public function onGuardToCodir(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_TO_CODIR);
    }
    public function onGuardToRejected(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_TO_REJECTED);
    }
    public function onGuardToAbandonned(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_TO_ABANDONNED);
    }
    public function onGuardToStarted(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_TO_STARTED);
    }
    public function onGuardToFinalised(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_TO_FINALISED);
    }
    public function onGuardToDeployed(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_TO_DEPLOYED);
    }
    public function onGuardToMeasured(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_TO_MEASURED);
    }
    public function onGuardToClotured(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_TO_CLOTURED);
    }
    public function onGuardUnDeployed(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_UN_DEPLOYED);
    }
    public function onGuardUnMeasured(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_UN_MEASURED);
    }
    public function onGuardUnClotured(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_UN_CLOTURED);
    }


    private function onGuard(GuardEvent $event, string $transition)
    {
        /** @var Action $action */
        $action = $event->getSubject();
        $workflowActionTransitionManager=new WorkflowActionTransitionManager(
            $event->getSubject(),
            $transition
        );

        if (!$workflowActionTransitionManager->can()) {
            $event->setBlocked(true);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'workflow.action.guard.ToStarted' => ['onGuardToStarted'],
            'workflow.action.guard.toCotech' => ['onGuardToCotech'],
            'workflow.action.guard.toCodir' => ['onGuardToCodir'],
            'workflow.action.guard.toRejected' => ['onGuardToRejected'],
            'workflow.action.guard.toAbandonned' => ['onGuardToAbandonned'],
            'workflow.action.guard.toFinalised' => ['onGuardToFinalised'],
            'workflow.action.guard.toDeployed' => ['onGuardToDeployed'],
            'workflow.action.guard.toMeasured' => ['onGuardToMeasured'],
            'workflow.action.guard.toClotured' => ['onGuardToClotured'],
            'workflow.action.guard.unClotured' => ['onGuardUnClotured'],
            'workflow.action.guard.unMeasured' => ['onGuardUnMeasured'],
            'workflow.action.guard.unDeployed' => ['onGuardUnDeployed']
        ];
    }



}
