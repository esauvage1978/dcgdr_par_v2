<?php


namespace App\Workflow\Transaction;


class TransitionToRejected extends TransitionAbstract
{
    public function getExplains(): array
    {
        return ['Si les données de l\'action sont insuffisantes, elle peut être refusée par l\'instance'];
    }
}