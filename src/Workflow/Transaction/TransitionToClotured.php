<?php


namespace App\Workflow\Transaction;


class TransitionToClotured extends TransitionAbstract
{
    public function getExplains(): array
    {
        return ['L\'action est en attente de clôture depuis  ' . $this->actionCheck->getDiffDateOfState() . ' jour(s)'];
    }

    public function check()
    {
        $this->actionCheck->checkMeasureValue();
        $this->actionCheck->checkMeasureContent();
    }
}