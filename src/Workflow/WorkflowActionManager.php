<?php

namespace App\Workflow;

use App\Entity\Action;
use App\Entity\User;
use App\Event\WorkflowTransitionEvent;
use App\Manager\ActionStateManager;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\StateMachine;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

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

    /**
     * @var Security
     */
    private $securityContext;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(
        ActionStateManager $actionStateManager,
        Registry $worflow,
        Security $securityContext,
        EventDispatcherInterface $dispatcher
    ) {
        $this->actionStateManager = $actionStateManager;
        $this->securityContext = $securityContext;
        $this->worflow = $worflow;
        $this->dispatcher = $dispatcher;
    }

    private function initialiseStateMachine(Action $action)
    {
        if (null == $this->stateMachine) {
            $this->stateMachine = $this->worflow->get($action, 'action');
        }
    }

    public function applyTransition(Action $action, string $transition, string $content, bool $automate = false)
    {
        $stateOld = $action->getState();

        $this->initialiseStateMachine($action);

        if ($this->stateMachine->can($action, $transition)) {
            $this->workflowActionTransitionManager = new WorkflowActionTransitionManager($action, $transition);

            $this->workflowActionTransitionManager->intialiseActionForTransition($content, $automate);

            $this->stateMachine->apply($action, $transition);

            if (!$automate) {
                /** @var User $user */
                $user = $this->securityContext->getToken()->getUser();
                $event = new WorkflowTransitionEvent($user, $action);
                $this->dispatcher->dispatch($event, WorkflowTransitionEvent::NAME);

                $this->actionStateManager->saveActionInHistory($action, $stateOld, $user);
            }

            return true;
        }

        return false;
    }
}
