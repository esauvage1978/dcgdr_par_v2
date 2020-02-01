<?php

namespace App\Security;

use App\Entity\Indicator;
use App\Entity\User;
use App\Workflow\WorkflowData;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class IndicatorVoter extends Voter
{
    const READ = 'read';
    const UPDATE = 'read';

    private $security;

    /**
     * @var ActionVoter
     */
    private $actionVoter;

    public function __construct(Security $security, ActionVoter $actionVoter)
    {
        $this->security = $security;
        $this->actionVoter=$actionVoter;
    }

    protected function supports(string $attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::READ,self::UPDATE])) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (null !== $subject and !$subject instanceof Indicator) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Indicator $indicator */
        $indicator = $subject;

        switch ($attribute) {
            case self::READ:
                return $this->canRead($indicator, $user);
            case self::UPDATE:
                return $this->canUpdate($indicator, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    public function canRead(Indicator $indicator, User $user)
    {
        if ($this->security->isGranted('ROLE_GESTIONNAIRE')) {
            return true;
        }


        return $this->canUpdate($indicator, $user);

    }

    public function canUpdate(Indicator $indicator, User $user)
    {
        $states=[
            WorkflowData::STATE_STARTED,
            WorkflowData::STATE_FINALISED,
        ];

        if (!in_array($indicator->getAction()->getState(),$states))
        {
            return false;
        }

        if ($this->security->isGranted('ROLE_GESTIONNAIRE')) {
            return true;
        }

        return $this->actionVoter->canUpdate($indicator->getAction(), $user);

    }

}
