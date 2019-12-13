<?php

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Validator\LocalValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;

abstract class ManagerAbstract implements ManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * @var LocalValidatorInterface
     */
    protected $validator;

    public function __construct(EntityManagerInterface $manager, LocalValidatorInterface $validator)
    {
        $this->manager = $manager;
        $this->validator = $validator;
    }

    public function save(EntityInterface $entity): bool
    {
        if (!$this->validator->isValid($entity)) {
            return false;
        }

        $this->manager->persist($entity);
        $this->manager->flush();

        return true;
    }

    public function getErrors(EntityInterface $entity)
    {
        return $this->validator->getErrors($entity);
    }

    public function remove(EntityInterface $entity)
    {
        $this->manager->remove($entity);
        $this->manager->flush();
    }
}
