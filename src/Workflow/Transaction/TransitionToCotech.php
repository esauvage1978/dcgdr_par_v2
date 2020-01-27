<?php

namespace App\Workflow\Transaction;

use App\Entity\Action;

class TransitionToCotech extends TransitionAbstract
{

    public function getExplains(): array
    {
        return ['L\'action devra être validée durant le COTECH'];
    }

    public function check()
    {
        $this->checkAll();
    }

}