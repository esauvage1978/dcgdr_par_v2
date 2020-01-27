<?php


namespace App\Workflow\Transaction;


class TransitionToCodir extends TransitionAbstract
{
    public function getExplains(): array
    {
        return ['L\'action devra être validée durant le CODIR'];
    }

    public function check()
    {
        $this->checkAll();
    }
}
