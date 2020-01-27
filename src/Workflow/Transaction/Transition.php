<?php

namespace App\Workflow\Transaction;

use App\Entity\Action;

interface Transition
{
    public function __construct(Action $action);
    public function can();
    public function getExplains(): array;
    public function getCheckMessages(): array;
    public function check();
    public function intialiseActionForTransition(bool $automate=false);
}
