<?php

namespace App\Helper;

use App\Dto\DeployementSearchDto;
use App\Entity\Deployement;
use App\Repository\DeployementRepository;
use App\Repository\UserRepository;
use App\Workflow\WorkflowData;

class DeployementJalonNotificator extends Messagor
{
    /**
     * @var mixed
     */
    private $users;

    /**
     * @var DeployementSearchDto
     */
    private $deployementSearchDto;

    /**
     * @var DeployementRepository
     */
    private $deployementRepository;

    /**
     * @var SendMail
     */
    private $sendMail;

    public function __construct(
        UserRepository $userRepository,
        DeployementSearchDto $deployementSearchDto,
        DeployementRepository $deployementRepository,
        SendMail $sendMail
    ) {
        $this->users = $userRepository->findAllWriterForDeployement();
        $this->deployementSearchDto = $deployementSearchDto;
        $this->deployementRepository = $deployementRepository;
        $this->sendMail=$sendMail;

        parent::__construct();
    }

    public function notifyJalonToday(): array
    {
        foreach ($this->users as $user) {
            $i=0;
            $this->deployementSearchDto
                ->setUserWriter($user->getId())
                ->setJalonFrom((new \DateTime())->format('Y-m-d 00:00:00'))
                ->setJalonTo((new \DateTime())->format('Y-m-d 23:59:59'))
                ->actionSearchDto->setStates(WorkflowData::STATES_DEPLOYEMENT_APPEND);

            /** @var Deployement[] $result */
            $result = $this->deployementRepository->findAllForDto($this->deployementSearchDto);

            if (!empty($result)) {

                $this->addMessage(
                    Messagor::TABULTATION.
                    ' Notification à '. $user->getName() .
                    ' [' .  $user->getEmail() . ']');

                $this->sendMail->send(
                    [
                        'user'=>$user,
                        'deployements'=>$result
                    ],
                    SendMail::DEPLOYEMENT_JALON_TODAY,
                    'PAR : Liste des déploiements dont la date de jalon est à aujourd\'hui'
                );

            }

        }
        return $this->getMessages();
    }
}
