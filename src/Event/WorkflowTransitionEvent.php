<?php

namespace App\Event;

use App\Entity\Action;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class WorkflowTransitionEvent extends Event
{
    public const NAME = 'worklow.transition';

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Action
     */
    protected $action;

    public function __construct(
        User $user,
        Action $action)
    {
        $this->user = $user;
        $this->action=$action;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * @return Action
     */
    public function getAction()
    {
        return $this->action;
    }
}
