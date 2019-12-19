<?php

namespace App\Manager;

use App\Validator\VecteurValidator;
use Doctrine\ORM\EntityManagerInterface;

class VecteurManager extends ManagerAbstract
{
    public function __construct(EntityManagerInterface $manager, VecteurValidator $validator)
    {
        parent::__construct($manager, $validator);
    }
}
