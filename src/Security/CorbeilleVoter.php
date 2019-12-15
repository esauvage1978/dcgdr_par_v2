<?php

namespace App\Security;

use App\Entity\Corbeille;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class CorbeilleVoter extends Voter
{
    const UPDATE = 'update';
    const DELETE = 'delete';
    const CREATE = 'create';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::CREATE, self::UPDATE, self::DELETE])) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (null !== $subject and !$subject instanceof Corbeille) {
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

        /** @var Corbeille $corbeille */
        $corbeille = $subject;

        switch ($attribute) {
            case self::UPDATE:
                return $this->canUpdate($corbeille, $user);
            case self::CREATE:
                return $this->canCreate($corbeille, $user);
            case self::DELETE:
                return $this->canDelete($corbeille, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canUpdate(Corbeille $corbeille, User $user)
    {
        if ($this->security->isGranted('ROLE_GESTIONNAIRE')) {
            return true;
        }

        if (null === $corbeille->getOrganisme()) {
            return false;
        }

        if(in_array( $corbeille->getOrganisme(), $user->getOrganismes()->toArray())
            && $corbeille->getOrganisme()->getAlterable()) {
            return true;
        }

        return false;
    }

    private function canCreate(?Corbeille $corbeille, User $user)
    {
        if ($this->security->isGranted('ROLE_GESTIONNAIRE_LOCAL')) {
            return true;
        }

        return false;
    }

    private function canDelete(Corbeille $corbeille, User $user)
    {
        if ($this->security->isGranted('ROLE_GESTIONNAIRE')) {
            return true;
        }

        return false;
    }
}
