<?php

namespace App\Manager;

use App\Entity\Action;
use App\Entity\Deployement;
use App\Entity\EntityInterface;
use App\Entity\Organisme;
use App\Repository\CorbeilleRepository;
use App\Repository\OrganismeRepository;
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

    public function createDeployement(
        Deployement $deployement,
        Action $action,
        OrganismeRepository $organismeRepository,
        string $organismeid,
        CorbeilleRepository $corbeilleRepository
    ){
        /** @var Organisme $organisme */
        $organisme = $organismeRepository->findOneBy(['id' => $organismeid]);

        $corbeilles = $corbeilleRepository->findBy(['organisme' => $organisme, 'showDefault' => true]);

        $deployement
            ->setAction($action)
            ->setOrganisme($organisme);

        foreach ($corbeilles as $corbeille) {
            $deployement->addWriter($corbeille);
        }

        $this->save($deployement);

        return $deployement;
    }
}
