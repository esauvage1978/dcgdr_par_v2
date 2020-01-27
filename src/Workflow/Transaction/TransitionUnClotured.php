<?php


namespace App\Workflow\Transaction;


class TransitionUnClotured extends TransitionAbstract
{
    public function getExplains(): array
    {
        return ['L\'action est en attente de clôture depuis  ' . $this->actionCheck->getDiffDateOfState() . ' jour(s)'];
    }

}
