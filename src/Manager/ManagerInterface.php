<?php

namespace App\Manager;

use App\Entity\EntityInterface;

interface  ManagerInterface
{

    public function save(EntityInterface $entity): bool;

    public function getErrors(EntityInterface $entity);

    public function remove(EntityInterface $entity);
}
