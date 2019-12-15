<?php

namespace App\Manager;

use App\Validator\CorbeilleValidator;
use Doctrine\ORM\EntityManagerInterface;

class CorbeilleManager extends ManagerAbstract
{
    public function __construct(EntityManagerInterface $manager,CorbeilleValidator $validator)
    {
        parent::__construct($manager,$validator);
    }
}
