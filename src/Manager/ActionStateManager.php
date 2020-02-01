<?php

namespace App\Manager;

use App\Entity\Action;
use App\Entity\ActionState;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Validator\ActionStateValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ActionStateManager extends ManagerAbstract
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        EntityManagerInterface $manager,
        ActionStateValidator $validator,
        UserRepository $userRepository
    ) {
        parent::__construct($manager, $validator);
        $this->userRepository = $userRepository;

    }

    public function saveActionInHistory(Action $action,string $initial_state, User $user)
    {

        $actionState = new ActionState();
        $actionState
            ->setUser($user)
            ->setAction($action)
            ->setContent($action->getContentState())
            ->setChangeAt(new \DateTime())
            ->setStateOld($initial_state)
            ->setStateNew($action->getState());

        $this->save($actionState);
    }
}
