<?php


namespace App\Workflow\Transaction;


class TransitionToClotured extends TransitionAbstract
{
    public function getExplains(): array
    {
        return ['L\'action peut être clôturé.'];
    }

    public function check()
    {
        $this->actionCheck->checkMeasureValue();
        $this->actionCheck->checkMeasureContent();
    }
}