<?php


namespace App\Workflow\Transaction;


use App\Workflow\WorkflowData;

class TransitionToMeasured extends TransitionAbstract
{
    public function getExplains(): array
    {
        $retour = [];
        $info = '';
        if ($this->actionCheck->checkRegionEndAtAfterNow()) {
            $info = 'L\'action basculera automatiquement à la date de fin de déploiement soit dans ' . $this->actionCheck->getDiffRegionEndAtAfterNow() . ' jour(s)';
            array_push($retour, $info);
        } else {
            $info = 'L\'action devait être terminée depuis ' . $this->actionCheck->getDiffRegionEndAtAfterNow() . ' jour(s)';
            array_push($retour, $info);
        }
        array_push($retour, 'Vous pouvez forcer la bascule de l\'action, la date de fin de déploiement sera positionnée à la date du jour.');
        return $retour;
    }


    public function intialiseActionForTransition(bool $automate = false)
    {
        if (!$automate) {
            $this->action->setContentState(
                $this->action->getContentState() .
                "<br/>Forçage de la bascule dans l'état " . WorkflowData::getNameOfState(WorkflowData::STATE_DEPLOYED) .
                "<br/> Ancienne date de fin de déploiement : " . $this->action->getRegionEndAt()->format("d/m/Y")
            );
            $this->action->setRegionEndAt(new \DateTime());
        }
    }
}