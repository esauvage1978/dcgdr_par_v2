<?php


namespace App\Workflow\Transaction;


class TransitionToDeploye extends TransitionAbstract
{

    public function getExplains(): array
    {
        return
            [
                'Les déploiements seront accessibles aux organismes concernés.',
                'Un mail sera envoyé pour les prévenir',
                'La bascule est automatique à la date de début de déploiement',
                'Vous pouvez forcer cette bascule, la date de début sera celle de ce jour.'
            ];
    }

    public function check()
    {
        $this->checkAll();
    }

    public function intialiseActionForTransition(bool $automate = false)
    {
        if (!$automate) {
            $this->action->setContentState(
                $this->action->getContentState() .
                "<br/> Ancienne date de début de déploiement : " . $this->action->getRegionStartAt()->format("d/m/Y")
            );
            $date=new \DateTime();
            $this->action->setRegionStartAt($date);

        }
    }

}