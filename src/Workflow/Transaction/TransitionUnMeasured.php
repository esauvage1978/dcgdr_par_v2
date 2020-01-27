<?php


namespace App\Workflow\Transaction;


class TransitionUnMeasured extends TransitionAbstract
{
    public function getExplains(): array
    {
        $retour=[];

        array_push($retour,'Vous pouvez forcer le redéploiement de l\'action, la date de fin de déploiement sera positionnée à la date du jour +3mois.');
        return $retour;
    }

    public function intialiseActionForTransition(bool $automate = false)
    {
        if (!$automate) {
            $this->action->setContentState(
                $this->action->getContentState() .
                "<br/> Ancienne date de fin de déploiement : " . $this->action->getRegionEndAt()->format("d/m/Y")
            );
            $date=new \DateTime('today +3 months');
            $this->action->setRegionEndAt($date);
        }
    }
}