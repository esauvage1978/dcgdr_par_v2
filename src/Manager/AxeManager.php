<?php

namespace App\Manager;

use App\Validator\AxeValidator;
use Doctrine\ORM\EntityManagerInterface;

class AxeManager extends ManagerAbstract
{
    public function __construct(EntityManagerInterface $manager, AxeValidator $validator)
    {
        parent::__construct($manager, $validator);
    }
}
