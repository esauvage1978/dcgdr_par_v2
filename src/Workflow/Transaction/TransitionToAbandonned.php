<?php


namespace App\Workflow\Transaction;


class TransitionToAbandonned extends TransitionAbstract
{
    public function getExplains(): array
    {
        return ['L\'action reste consultable et ce changement d\'état est réversible.'];
    }
}