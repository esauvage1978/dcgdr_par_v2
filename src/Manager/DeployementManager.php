<?php

namespace App\Manager;

use App\Validator\DeployementValidator;
use Doctrine\ORM\EntityManagerInterface;

class DeployementManager extends ManagerAbstract
{
    public function __construct(EntityManagerInterface $manager, DeployementValidator $validator)
    {
        parent::__construct($manager, $validator);
    }
}
