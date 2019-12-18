<?php

namespace App\Manager;

use App\Validator\ThematiqueValidator;
use Doctrine\ORM\EntityManagerInterface;

class ThematiqueManager extends ManagerAbstract
{
    public function __construct(EntityManagerInterface $manager, ThematiqueValidator $validator)
    {
        parent::__construct($manager, $validator);
    }
}
