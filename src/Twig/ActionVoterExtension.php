<?php

namespace App\Twig;

use App\Entity\Action;
use App\Entity\User;
use App\Security\ActionVoter;
use Doctrine\Common\Collections\Collection;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Symfony\Component\Security\Core\Security;

class ActionVoterExtension extends AbstractExtension
{
    /**
     * @var ActionVoter
     */
    private $actionVoter;

    /**
     * @var Security
     */
    private $security;

    public function __construct(
        Security $security,
        ActionVoter $actionVoter
    ) {
        $this->security = $security;
        $this->actionVoter=$actionVoter;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('actionCanRead', [$this, 'actionCanRead']),
            new TwigFilter('actionCanUpdate', [$this, 'actionCanUpdate']),
        ];
    }

    public function actionCanRead(Action $action)
    {
        /** @var User $user */
        $user = $this->security->getToken()->getUser();

        return $this->actionVoter->canRead($action, $user);
    }

    public function actionCanUpdate(Action $action)
    {
        /** @var User $user */
        $user = $this->security->getToken()->getUser();

        return $this->actionVoter->canUpdate($action, $user);
    }
}
