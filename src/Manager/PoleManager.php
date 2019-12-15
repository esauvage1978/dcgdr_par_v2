<?php

namespace App\Manager;

use App\Validator\PoleValidator;
use Doctrine\ORM\EntityManagerInterface;

class PoleManager extends ManagerAbstract
{
    public function __construct(EntityManagerInterface $manager, PoleValidator $validator)
    {
        parent::__construct($manager, $validator);
    }
}
