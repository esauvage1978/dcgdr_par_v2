<?php

namespace App\Twig;

use App\Entity\Deployement;
use App\Entity\User;
use App\Security\DeployementVoter;
use Symfony\Component\Security\Core\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DeployementVoterExtension extends AbstractExtension
{
    /**
     * @var DeployementVoter
     */
    private $deployementVoter;

    /**
     * @var Security
     */
    private $security;

    public function __construct(
        Security $security,
        DeployementVoter $deployementVoter
    )
    {
        $this->security = $security;
        $this->deployementVoter = $deployementVoter;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('deployementCanAppendRead', [$this, 'deployementCanAppendRead']),
            new TwigFilter('deployementCanAppendUpdate', [$this, 'deployementCanAppendUpdate']),
            new TwigFilter('deployementCanUpdate', [$this, 'deployementCanUpdate']),
            new TwigFilter('deployementCanDelete', [$this, 'deployementCanDelete']),
        ];
    }

    public function deployementCanAppendRead(Deployement $deployement)
    {
        /** @var User $user */
        $user = $this->security->getToken()->getUser();

        return $this->deployementVoter->canAppendRead($deployement, $user);
    }

    public function deployementCanAppendUpdate(Deployement $deployement)
    {
        /** @var User $user */
        $user = $this->security->getToken()->getUser();

        return $this->deployementVoter->canAppendUpdate($deployement, $user);
    }

    public function deployementCanUpdate(Deployement $deployement)
    {
        /** @var User $user */
        $user = $this->security->getToken()->getUser();

        return $this->deployementVoter->canUpdate($deployement, $user);
    }

    public function deployementCanDelete(Deployement $deployement)
    {
        /** @var User $user */
        $user = $this->security->getToken()->getUser();

        return $this->deployementVoter->canDelete($deployement, $user);
    }
}
