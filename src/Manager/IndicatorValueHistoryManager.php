<?php

namespace App\Manager;

use App\Validator\IndicatorValueHistoryValidator;
use Doctrine\ORM\EntityManagerInterface;

class IndicatorValueHistoryManager extends ManagerAbstract
{
    public function __construct(EntityManagerInterface $manager,
                                IndicatorValueHistoryValidator $validator
    ) {
        parent::__construct($manager, $validator);
    }
}
