<?php

namespace App\Twig;

use App\Entity\Deployement;
use App\Entity\Indicator;
use App\Entity\User;
use App\Security\DeployementVoter;
use Symfony\Component\Security\Core\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DeployementExtension extends AbstractExtension
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
            new TwigFilter('organismeIndicateurDeployed', [$this, 'organismeIndicateurDeployed']),
            new TwigFilter('organismeDeployed', [$this, 'organismeDeployed']),
        ];
    }

    public function organismeIndicateurDeployed(Deployement $deployement)
    {
        $nbr=0;
         foreach($deployement->getIndicatorValues() as $indcateurValue)
         {
             if( $indcateurValue->getEnable() && $indcateurValue->getIndicator()->getEnable()) {
                 $nbr++;
             }
         }
         return $nbr;
    }
    public function organismeDeployed(Indicator $indicator)
    {
        $nbr=0;
        foreach($indicator->getIndicatorValues() as $indcateurValue)
        {
            if( $indcateurValue->getEnable() && $indicator->getEnable()) {
                $nbr++;
            }
        }
        return $nbr;
    }

}
