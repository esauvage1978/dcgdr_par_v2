<?php

namespace App\Helper;

use App\Dto\ActionSearchDto;
use App\Entity\User;
use App\Repository\ActionRepository;
use App\Workflow\WorkflowData;
use Symfony\Component\Security\Core\Security;

class ActionFilter
{
    /**
     * @var ActionRepository
     */
    private $repository;

    /**
     * @var ActionSearchDto
     */
    private $actionSearchDto;

    private $security;

    public function __construct(
        ActionRepository $repository,
        ActionSearchDto $actionSearchDto,
        Security $security
    )
    {
        $this->repository = $repository;
        $this->actionSearchDto = $actionSearchDto;
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
                $this->actionSearchDto->setUserWriter($user->getId());
                $resultRepo = $this->repository->findAllForDto($this->actionSearchDto, ActionRepository::FILTRE_DTO_INIT_AJAX);
                break;

            case WorkflowData::STATE_STARTED:
                $this->actionSearchDto->setUserWriter($user->getId());
                $this->actionSearchDto->setState(WorkflowData::STATE_STARTED);
                $resultRepo = $this->repository->findAllForDto($this->actionSearchDto, ActionRepository::FILTRE_DTO_INIT_AJAX);
                break;
            case WorkflowData::STATE_COTECH:
                $this->actionSearchDto->setUserWriter($user->getId());
                $this->actionSearchDto->setState(WorkflowData::STATE_COTECH);
                $resultRepo = $this->repository->findAllForDto($this->actionSearchDto, ActionRepository::FILTRE_DTO_INIT_AJAX);
                break;
            case WorkflowData::STATE_CODIR:
                $this->actionSearchDto->setUserWriter($user->getId());
                $this->actionSearchDto->setState(WorkflowData::STATE_CODIR);
                $resultRepo = $this->repository->findAllForDto($this->actionSearchDto, ActionRepository::FILTRE_DTO_INIT_AJAX);
                break;
            case 'valider_'.WorkflowData::STATE_COTECH:
                $this->actionSearchDto->setUserValider($user->getId());
                $this->actionSearchDto->setState(WorkflowData::STATE_COTECH);
                $resultRepo = $this->repository->findAllForDto($this->actionSearchDto, ActionRepository::FILTRE_DTO_INIT_AJAX);
                break;
            case 'valider_'.WorkflowData::STATE_CODIR:
                $this->actionSearchDto->setUserValider($user->getId());
                $this->actionSearchDto->setState(WorkflowData::STATE_CODIR);
                $resultRepo = $this->repository->findAllForDto($this->actionSearchDto, ActionRepository::FILTRE_DTO_INIT_AJAX);
                break;
            case WorkflowData::STATE_REJECTED:
                $this->actionSearchDto->setUserWriter($user->getId());
                $this->actionSearchDto->setState(WorkflowData::STATE_REJECTED);
                $resultRepo = $this->repository->findAllForDto($this->actionSearchDto, ActionRepository::FILTRE_DTO_INIT_AJAX);
                break;
            case WorkflowData::STATE_FINALISED:
                $this->actionSearchDto->setUserWriter($user->getId());
                $this->actionSearchDto->setState(WorkflowData::STATE_FINALISED);
                $resultRepo = $this->repository->findAllForDto($this->actionSearchDto, ActionRepository::FILTRE_DTO_INIT_AJAX);
                break;
            case WorkflowData::STATE_DEPLOYED:
                $this->actionSearchDto->setUserWriter($user->getId());
                $this->actionSearchDto->setState(WorkflowData::STATE_DEPLOYED);
                $resultRepo = $this->repository->findAllForDto($this->actionSearchDto, ActionRepository::FILTRE_DTO_INIT_AJAX);
                break;
            case WorkflowData::STATE_MEASURED:
                $this->actionSearchDto->setUserWriter($user->getId());
                $this->actionSearchDto->setState(WorkflowData::STATE_MEASURED);
                $resultRepo = $this->repository->findAllForDto($this->actionSearchDto, ActionRepository::FILTRE_DTO_INIT_AJAX);
                break;
            case WorkflowData::STATE_CLOTURED:
                $this->actionSearchDto->setUserWriter($user->getId());
                $this->actionSearchDto->setState(WorkflowData::STATE_CLOTURED);
                $resultRepo = $this->repository->findAllForDto($this->actionSearchDto, ActionRepository::FILTRE_DTO_INIT_AJAX);
                break;
            case WorkflowData::STATE_ABANDONNED:
                $this->actionSearchDto->setUserWriter($user->getId());
                $this->actionSearchDto->setState(WorkflowData::STATE_ABANDONNED);
                $resultRepo = $this->repository->findAllForDto($this->actionSearchDto, ActionRepository::FILTRE_DTO_INIT_AJAX);
                break;
            case 'without_jalon':
                $this->actionSearchDto
                    ->setUserWriter($user->getId())
                    ->setJalonNotPresent(true);

                $resultRepo = $this->repository->findAllForDto($this->actionSearchDto, ActionRepository::FILTRE_DTO_INIT_AJAX);
                $complement = ' - Sans jalon définie';
                break;
            case 'jalon_to_late':
                $this->actionSearchDto
                    ->setUserWriter($user->getId())
                    ->setJalonFrom((new \DateTime())->format('Y-m-d'))
                    ->setJalonOperator('<');

                $resultRepo = $this->repository->findAllForDto($this->actionSearchDto, ActionRepository::FILTRE_DTO_INIT_AJAX);
                $complement = ' - Avec jalon dépassé';
                break;
            case 'jalon_to_near':
                $this->actionSearchDto
                    ->setUserWriter($user->getId())
                    ->setJalonFrom((new \DateTime())->format('Y-m-d'))
                    ->setJalonTo(date('Y-m-d', strtotime((new \DateTime())->format('Y-m-d') . ' +8 day')));

                $resultRepo = $this->repository->findAllForDto($this->actionSearchDto, ActionRepository::FILTRE_DTO_INIT_AJAX);
                $complement = ' -  Avec un jalon à traiter dans moins de 7 jours';
                break;
            case 'jalon_to_come_up':
                $this->actionSearchDto
                    ->setUserWriter($user->getId())
                    ->setJalonFrom(date('Y-m-d', strtotime((new \DateTime())->format('Y-m-d') . ' +8 day')))
                    ->setJalonOperator('>');

                $resultRepo = $this->repository->findAllForDto($this->actionSearchDto, ActionRepository::FILTRE_DTO_INIT_AJAX);
                $complement = ' - Avec un jalon à traiter dans plus de 7 jours';
                break;
        }

        return [
            'actions' => $resultRepo,
            'complement' => $complement,
            'nbr' => count($resultRepo),
        ];
    }
}
