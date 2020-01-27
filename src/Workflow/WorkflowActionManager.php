<?php

namespace App\Workflow;

use App\Entity\Action;
use App\Manager\ActionStateManager;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\StateMachine;

class WorkflowActionManager
{

    /**
     * @var ActionStateManager
     */
    private $actionStateManager;

    /**
     * @var Registry
     */
    private $worflow;
    /**
     * @var StateMachine
     */
    private $stateMachine;

    /**
     * @var WorkflowActionTransitionManager
     */
    private $workflowActionTransitionManager;

    public function __construct(ActionStateManager $actionStateManager, Registry $worflow)
    {
        $this->actionStateManager = $actionStateManager;

        $this->worflow = $worflow;
    }

    private function initialiseStateMachine(Action $action)
    {
        if (null == $this->stateMachine) {
            $this->stateMachine = $this->worflow->get($action, 'action');
        }
    }


    public function applyTransition(Action $action, string $transition, string $content, bool $automate=false)
    {

        $stateOld=$action->getState();

        $this->initialiseStateMachine($action);

        if ($this->stateMachine->can($action, $transition)) {

            $this->workflowActionTransitionManager= new WorkflowActionTransitionManager($action,$transition);
            $this->workflowActionTransitionManager->intialiseActionForTransition($content,$automate);
dump($action);
            $this->stateMachine->apply($action, $transition);

            $this->actionStateManager->saveActionInHistory($action, $stateOld);

            return true;
        }

        return false;
    }


}
