<?php


namespace App\Workflow;


use App\Entity\Action;
use App\Workflow\TransitionToCotech;

class WorkflowActionTransitionManager
{
    /**
     * @var Action
     */
    private $action;

    /**
     * @var string
     */
    private $transition;


    public function __construct(Action $action,string $transition='')
    {
        $this->action=$action;
        $this->transition=$transition;
    }

    public function intialiseActionForTransition(string $content, bool $automate=false)
    {
        $this->action->setStateAt(new \DateTime());
        $this->action->setContentState($content);
        $object = __NAMESPACE__ . '\Transaction\Transition' . ucfirst( $this->transition);
        $instance=new $object($this->action);
        $instance->intialiseActionForTransition($automate);
    }

    public function can(): bool
    {
        $object = __NAMESPACE__ . '\Transaction\Transition' . ucfirst( $this->transition);
        $instance=new $object($this->action);
        return $instance->can();
    }
}