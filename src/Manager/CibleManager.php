<?php

namespace App\Manager;

use App\Validator\CibleValidator;
use Doctrine\ORM\EntityManagerInterface;

class CibleManager extends ManagerAbstract
{
    public function __construct(EntityManagerInterface $manager, CibleValidator $validator)
    {
        parent::__construct($manager, $validator);
    }
}
