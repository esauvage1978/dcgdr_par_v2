<?php


namespace App\Workflow\Transaction;


class TransitionUnClotured extends TransitionAbstract
{
    public function getExplains(): array
    {
        return ['L\'action peut être réouverte pour apporter des précisions complémentaires.'];
    }

}
