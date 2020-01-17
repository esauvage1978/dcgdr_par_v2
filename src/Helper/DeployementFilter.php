<?php

namespace App\Helper;

use App\Dto\DeployementSearchDto;
use App\Entity\User;
use App\Repository\DeployementRepository;
use Symfony\Component\Security\Core\Security;

class DeployementFilter
{
    /**
     * @var DeployementRepository
     */
    private $repository;

    /**
     * @var DeployementSearchDto
     */
    private $deploiementSearchDto;

    private $security;

    public function __construct(
        DeployementRepository $repository,
        DeployementSearchDto $deploiementSearchDto,
        Security $security
    ) {
        $this->repository = $repository;
        $this->deploiementSearchDto = $deploiementSearchDto;
        $this->security = $security;
    }

    public function getData(?string $filter): array
    {
        $resultRepo = null;
        $complement = '';

        /** @var User $user */
        $user = $this->security->getToken()->getUser();

        switch ($filter) {
            default:
                $this->deploiementSearchDto->setUserWriter($user->getId());
                $resultRepo = $this->repository->findAllForDto($this->deploiementSearchDto);
                break;
            case 'without_jalon':
                $this->deploiementSearchDto
                    ->setUserWriter($user->getId())
                    ->setJalonNotPresent(true);

                $resultRepo = $this->repository->findAllForDto($this->deploiementSearchDto);
                $complement = ' - Sans jalon définie';
                break;
            case 'jalon_to_late':
                $this->deploiementSearchDto
                    ->setUserWriter($user->getId())
                    ->setJalonFrom((new \DateTime())->format('Y-m-d'))
                    ->setJalonOperator('<');

                $resultRepo = $this->repository->findAllForDto($this->deploiementSearchDto);
                $complement = ' - Avec jalon dépassé';
                break;
            case 'jalon_to_near':
                $this->deploiementSearchDto
                    ->setUserWriter($user->getId())
                    ->setJalonFrom((new \DateTime())->format('Y-m-d'))
                    ->setJalonTo(date('Y-m-d', strtotime((new \DateTime())->format('Y-m-d').' +8 day')));

                $resultRepo = $this->repository->findAllForDto($this->deploiementSearchDto);
                $complement = ' -  Avec un jalon à traiter dans moins de 7 jours';
                break;
            case 'jalon_to_come_up':
                $this->deploiementSearchDto
                    ->setUserWriter($user->getId())
                    ->setJalonFrom(date('Y-m-d', strtotime((new \DateTime())->format('Y-m-d').' +8 day')))
                    ->setJalonOperator('>');

                $resultRepo = $this->repository->findAllForDto($this->deploiementSearchDto);
                $complement = ' - Avec un jalon à traiter dans plus de 7 jours';
                break;
        }

        return  [
            'deployements' => $resultRepo,
            'complement' => $complement,
            'nbr' => count($resultRepo),
        ];
    }
}
