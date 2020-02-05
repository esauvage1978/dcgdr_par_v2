<?php

namespace App\Workflow;

use App\Entity\Action;
use App\Entity\User;
use App\Event\WorkflowTransitionEvent;
use App\Manager\ActionStateManager;
use App\Repository\UserRepository;
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
    private $workflow;
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

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        ActionStateManager $actionStateManager,
        Registry $workflow,
        Security $securityContext,
        EventDispatcherInterface $dispatcher,
        UserRepository $userRepository
    ) {
        $this->actionStateManager = $actionStateManager;
        $this->securityContext = $securityContext;
        $this->workflow = $workflow;
        $this->dispatcher = $dispatcher;
        $this->userRepository = $userRepository;
    }

    private function initialiseStateMachine(Action $action)
    {
        if (null == $this->stateMachine) {
            $this->stateMachine = $this->workflow->get($action, 'action');
        }
    }

    public function applyTransition(Action $action, string $transition, string $content, bool $automate = false)
    {
        $stateOld = $action->getState();

        $this->initialiseStateMachine($action);

        if ($this->stateMachine->can($action, $transition)) {
            $this->apply_change_state($action, $transition, $automate, $content);

            $user = $this->loadUser($automate);

            $this->send_mails($user, $action);

            $this->historisation($user, $action, $stateOld);

            return true;
        }

        return false;
    }

    private function apply_change_state(Action $action, string $transition, bool $automate, string $content)
    {
        $this->workflowActionTransitionManager = new WorkflowActionTransitionManager($action, $transition);
        $this->workflowActionTransitionManager->intialiseActionForTransition($content, $automate);
        $this->stateMachine->apply($action, $transition);
    }

    private function send_mails(User $user, Action $action)
    {
        $event = new WorkflowTransitionEvent($user, $action);
        $this->dispatcher->dispatch($event, WorkflowTransitionEvent::NAME);
    }

    private function historisation(User $user, Action $action, string $stateOld)
    {
        $this->actionStateManager->saveActionInHistory($action, $stateOld, $user);
    }

    private function loadUser(bool $automate)
    {
        if (!$automate) {
            return $this->securityContext->getToken()->getUser();
        } else {
            return $this->userRepository->find(1);
        }
    }
}
