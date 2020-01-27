<?php


namespace App\Workflow\Transaction;


use App\Entity\Action;
use App\Workflow\ActionCheck;

class TransitionAbstract implements Transition
{
    /**
     * @var Action
     */
    protected $action;



    /**
     * @var ActionCheck
     */
    protected $actionCheck;

    public function __construct(Action $action)
    {
        $this->action=$action;
        $this->actionCheck=new ActionCheck($action);
    }

    public function can(): bool
    {
        $this->check();
        return !$this->actionCheck->hasMessageError();
    }

    public function check()
    {

    }

    public function getExplains(): array
    {

    }

    public function getCheckMessages(): array
    {
        $this->check();
        return $this->actionCheck->getMessages();
    }

    public function checkAll()
    {
        $this->actionCheck->checkName();
        $this->actionCheck->checkReference();
        $this->actionCheck->checkRegionStartAt();
        $this->actionCheck->checkRegionEndAt();
        $this->actionCheck->checkRegionStartAtBeforeRegionEnAt();
        $this->actionCheck->checkCadrage();
        $this->actionCheck->checkCorbeillePilotage();
        $this->actionCheck->checkCorbeilleValidation();
        $this->actionCheck->checkIndicators();
        $this->actionCheck->checkOrganismes();
        $this->actionCheck->checkDeploiement();
    }

    public function intialiseActionForTransition(bool $automate=false)
    {

    }
}
