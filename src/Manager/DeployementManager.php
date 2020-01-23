<?php

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Validator\DeployementValidator;
use Doctrine\ORM\EntityManagerInterface;

class DeployementManager extends ManagerAbstract
{
    public function __construct(EntityManagerInterface $manager, DeployementValidator $validator)
    {
        parent::__construct($manager, $validator);
    }

    public function initialise(EntityInterface $entity): void
    {
        foreach ($entity->getDeployementFiles() as $deployementFile)
        {
            $deployementFile->setDeployement($entity);
        }

        foreach ($entity->getDeployementLinks() as $deployementLink)
        {
            $deployementLink->setDeployement($entity);
        }
    }
}
