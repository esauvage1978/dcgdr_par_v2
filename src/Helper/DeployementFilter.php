<?php

namespace App\Helper;

use App\Dto\DeployementSearchDto;
use App\Entity\User;
use App\Repository\DeployementRepository;
use App\Workflow\WorkflowData;
use DateTime;
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
        $dto = $this->deploiementSearchDto;

        /** @var User $user */
        $user = $this->security->getToken()->getUser();

        switch ($filter) {
            case 'without_jalon':
                $dto
                    ->setUserWriter($user->getId())
                    ->setHasDateEndOfDeployement(DeployementSearchDto::DATE_STATUS_NULL)
                    ->setJalonNotPresent(true)
                    ->actionSearchDto->setStates(WorkflowData::STATES_DEPLOYEMENT_APPEND);

                $resultRepo = $this->repository->findAllForDto($this->deploiementSearchDto);
                $complement = ' - Sans jalon définie';
                break;
            case 'jalon_to_late':
                $dto
                    ->setUserWriter($user->getId())
                    ->setHasDateEndOfDeployement(DeployementSearchDto::DATE_STATUS_NULL)
                    ->setJalonFrom((new DateTime())->format('Y-m-d'))
                    ->setJalonOperator('<')
                    ->actionSearchDto->setStates(WorkflowData::STATES_DEPLOYEMENT_APPEND);

                $resultRepo = $this->repository->findAllForDto($this->deploiementSearchDto);
                $complement = ' - Avec jalon dépassé';
                break;
            case 'jalon_to_near':
                $dto
                    ->setUserWriter($user->getId())
                    ->setHasDateEndOfDeployement(DeployementSearchDto::DATE_STATUS_NULL)
                    ->setJalonFrom((new DateTime())->format('Y-m-d'))
                    ->setJalonTo(date('Y-m-d', strtotime((new DateTime())->format('Y-m-d').' +8 day')))
                    ->actionSearchDto->setStates(WorkflowData::STATES_DEPLOYEMENT_APPEND);

                $resultRepo = $this->repository->findAllForDto($this->deploiementSearchDto);
                $complement = ' -  Avec un jalon à traiter dans moins de 7 jours';
                break;
            case 'jalon_to_come_up':
                $dto
                    ->setUserWriter($user->getId())
                    ->setHasDateEndOfDeployement(DeployementSearchDto::DATE_STATUS_NULL)
                    ->setJalonFrom(date('Y-m-d', strtotime((new DateTime())->format('Y-m-d').' +8 day')))
                    ->setJalonOperator('>')
                    ->actionSearchDto->setStates(WorkflowData::STATES_DEPLOYEMENT_APPEND);

                $resultRepo = $this->repository->findAllForDto($this->deploiementSearchDto);
                $complement = ' - Avec un jalon à traiter dans plus de 7 jours';
                break;
            default:
                if (strstr($filter, 'piloteorganisme_')) {
                    $dto
                        ->setOrganismeId(substr($filter, strlen('piloteorganisme_') - strlen($filter)))
                        ->setHasDateEndOfDeployement(DeployementSearchDto::DATE_STATUS_NULL)
                        ->setHasWriters(DeployementSearchDto::WRITERS_PRESENT)
                        ->actionSearchDto->setStates(WorkflowData::STATES_DEPLOYEMENT_APPEND);

                    $resultRepo=$this->repository->findAllForDto($this->deploiementSearchDto);
                    break;
                } elseif (strstr($filter, 'organisme_')) {
                    $dto
                        ->setOrganismeId(substr($filter, strlen('organisme_') - strlen($filter)))
                        ->setHasDateEndOfDeployement(DeployementSearchDto::DATE_STATUS_ALL)
                        ->actionSearchDto->setStates(WorkflowData::STATES_DEPLOYEMENT_APPEND);

                    $resultRepo = $this->repository->findAllForDto($this->deploiementSearchDto);
                    break;
                }
            // no break
            case 'all':
                $dto
                    ->setUserWriter($user->getId())
                    ->setHasDateEndOfDeployement(DeployementSearchDto::DATE_STATUS_NULL)
                    ->actionSearchDto->setStates(WorkflowData::STATES_DEPLOYEMENT_APPEND);
                $resultRepo = $this->repository->findAllForDto($this->deploiementSearchDto);
                break;
            case 'allterminated':
                $dto
                    ->setUserWriter($user->getId())
                    ->setHasDateEndOfDeployement(DeployementSearchDto::DATE_STATUS_NOT_NULL)
                    ->actionSearchDto->setStates(WorkflowData::STATES_DEPLOYEMENT_APPEND);
                $resultRepo = $this->repository->findAllForDto($this->deploiementSearchDto);
                $complement = ' - L\'organisme a terminé d\'enrichir ce déploiement';
                break;
        }

        return [
            'deployements' => $resultRepo,
            'complement' => $complement,
            'nbr' => count($resultRepo),
        ];
    }
}
