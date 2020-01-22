<?php

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Validator\ActionValidator;
use Doctrine\ORM\EntityManagerInterface;

class ActionManager extends ManagerAbstract
{
    public function __construct(EntityManagerInterface $manager, ActionValidator $validator)
    {
        parent::__construct($manager, $validator);
    }

    public function initialise(EntityInterface $entity): void
    {
        foreach ($entity->getActionFiles() as $actionFile)
        {
            $actionFile->setAction($entity);
        }

        foreach ($entity->getActionLinks() as $actionLink)
        {
            $actionLink->setAction($entity);
        }
    }

}
