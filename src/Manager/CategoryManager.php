<?php

namespace App\Manager;

use App\Validator\CategoryValidator;
use Doctrine\ORM\EntityManagerInterface;

class CategoryManager extends ManagerAbstract
{
    public function __construct(EntityManagerInterface $manager, CategoryValidator $validator)
    {
        parent::__construct($manager, $validator);
    }
}
