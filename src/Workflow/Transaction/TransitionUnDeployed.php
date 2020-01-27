<?php


namespace App\Workflow\Transaction;


class TransitionUnDeployed extends TransitionAbstract
{
    public function getExplains(): array
    {
        return ['Permet de remettre l\'action en édition pour la modifier.',
            'Les données saisies lors des déploiements sont conservées.',
            'La date de début de déploiement sera positionnée à la date du jour +3mois',
            'la date de fin de déploiement sera positionnée à la date du jour +6mois'];
    }

    public function intialiseActionForTransition(bool $automate = false)
    {
        if (!$automate) {
            $this->action->setContentState(
                $this->action->getContentState() .
                "<br/> Ancienne date de début de déploiement : " . $this->action->getRegionStartAt()->format("d/m/Y") .
                "<br/> Ancienne date de fin de déploiement : " . $this->action->getRegionEndAt()->format("d/m/Y")
            );
            $date=new \DateTime('today +3 months');
            $this->action->setRegionStartAt($date);
            $date=new \DateTime('today +6 months');
            $this->action->setRegionEndAt($date);
        }
    }
}