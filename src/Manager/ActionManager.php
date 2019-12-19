<?php

namespace App\Manager;

use App\Validator\ActionValidator;
use Doctrine\ORM\EntityManagerInterface;

class ActionManager extends ManagerAbstract
{
    public function __construct(EntityManagerInterface $manager, ActionValidator $validator)
    {
        parent::__construct($manager, $validator);
    }
}
