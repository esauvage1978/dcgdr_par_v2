<?php

namespace App\Manager;

use App\Validator\IndicatorValidator;
use Doctrine\ORM\EntityManagerInterface;

class IndicatorManager extends ManagerAbstract
{


    public function __construct(EntityManagerInterface $manager, IndicatorValidator $validator)
    {
        parent::__construct($manager, $validator);
    }
}
