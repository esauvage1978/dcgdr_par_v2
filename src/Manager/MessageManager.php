<?php

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Validator\MessageValidator;
use Doctrine\ORM\EntityManagerInterface;

class MessageManager extends ManagerAbstract
{
    public function __construct(EntityManagerInterface $manager, MessageValidator $validator)
    {
        parent::__construct($manager, $validator);
    }

    public function initialise(EntityInterface $entity): void
    {
        $entity->setModifyAt(new \DateTime());
    }
}
