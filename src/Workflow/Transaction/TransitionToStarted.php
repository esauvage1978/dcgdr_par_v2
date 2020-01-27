<?php


namespace App\Workflow\Transaction;


class TransitionToStarted extends TransitionAbstract
{
    public function getExplains(): array
    {
        return [
            'L\'action peut être renvoyé aux pilotes pour qu\'elle soit retravaillée',
            'Celle-ci reprendra le cycle de validation (Cotech, Codir...)'];
    }
}