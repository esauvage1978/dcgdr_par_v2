<?php

namespace App\Manager;

use App\Validator\OrganismeValidator;
use Doctrine\ORM\EntityManagerInterface;

class OrganismeManager extends ManagerAbstract
{
    public function __construct(EntityManagerInterface $manager,OrganismeValidator $validator)
    {
        parent::__construct($manager,$validator);
    }
}
