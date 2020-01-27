<?php


namespace App\Workflow\Transaction;


class TransitionToFinalised extends TransitionAbstract
{

    public function getExplains(): array
    {
        return
            [
                'Après validation, La méthodologie de l\'action sera complétée par les pilotes.'
            ];
    }

    public function check()
    {
        $this->checkAll();
    }

}