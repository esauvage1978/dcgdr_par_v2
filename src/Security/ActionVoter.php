<?php

namespace App\Security;

use App\Entity\Action;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class ActionVoter extends Voter
{
    const READ = 'read';
    const UPDATE = 'read';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::READ,self::UPDATE])) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (null !== $subject and !$subject instanceof Action) {
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

        /** @var Action $action */
        $action = $subject;

        switch ($attribute) {
            case self::READ:
                return $this->canRead($action, $user);
            case self::UPDATE:
                return $this->canUpdate($action, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    public function canRead(Action $action, User $user)
    {
        if ($this->security->isGranted('ROLE_GESTIONNAIRE')) {
            return true;
        }

        if($action->getShowAll()) {
            return true;
        }

        foreach ($action->getReaders() as $corbeille) {
            if (in_array($user, $corbeille->getUsers()->toArray())) {
                return true;
            }
        }

        return $this->canUpdate($action,$user);
    }

    public function canUpdate(Action $action, User $user)
    {
        if( $action->getCategory()->getThematique()->getPole()->getAxe()->getArchiving()) {
            return false;
        }

        if ($this->security->isGranted('ROLE_GESTIONNAIRE')) {
            return true;
        }

        foreach ($action->getWriters() as $corbeille) {
            if (in_array($user, $corbeille->getUsers()->toArray())) {
                return true;
            }
        }

        foreach ($action->getValiders() as $corbeille) {
            if (in_array($user, $corbeille->getUsers()->toArray())) {
                return true;
            }
        }

        return false;
    }

}
