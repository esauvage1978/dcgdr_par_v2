<?php

namespace App\Twig;

use App\Entity\Indicator;
use App\Entity\User;
use App\Security\IndicatorVoter;
use Symfony\Component\Security\Core\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class IndicatorVoterExtension extends AbstractExtension
{
    /**
     * @var IndicatorVoter
     */
    private $indicatorVoter;

    /**
     * @var Security
     */
    private $security;

    public function __construct(
        Security $security,
        IndicatorVoter $indicatorVoter
    )
    {
        $this->security = $security;
        $this->indicatorVoter = $indicatorVoter;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('indicatorCanUpdate', [$this, 'indicatorCanUpdate']),
        ];
    }

    public function indicatorCanUpdate(Indicator $indicator)
    {
        /** @var User $user */
        $user = $this->security->getToken()->getUser();

        return $this->indicatorVoter->canUpdate($indicator, $user);
    }

}
