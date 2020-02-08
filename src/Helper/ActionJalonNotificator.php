<?php

namespace App\Helper;

use App\Dto\ActionSearchDto;
use App\Entity\Action;
use App\Repository\ActionRepository;
use App\Repository\UserRepository;
use App\Workflow\WorkflowData;

class ActionJalonNotificator extends Messagor
{
    /**
     * @var mixed
     */
    private $usersWriter;

    /**
     * @var mixed
     */
    private $usersValider;

    /**
     * @var ActionSearchDto
     */
    private $actionSearchDto;

    /**
     * @var ActionRepository
     */
    private $actionRepository;

    /**
     * @var SendMail
     */
    private $sendMail;

    public function __construct(
        UserRepository $userRepository,
        ActionSearchDto $actionSearchDto,
        ActionRepository $actionRepository,
        SendMail $sendMail
    ) {
        $this->usersWriter = $userRepository->findAllWriterForAction();
        $this->usersValider = $userRepository->findAllValiderForAction();
        $this->actionSearchDto = $actionSearchDto;
        $this->actionRepository = $actionRepository;
        $this->sendMail = $sendMail;

        parent::__construct();
    }

    public function notifyJalonToday()
    {
        $this->addMessage('PILOTES :');
        $this->notifyJalonTodayForGroup($this->usersWriter, WorkflowData::STATES_ACTION_UPDATE_PILOTES);


        $this->addMessage('VALIDEURS :');
        $this->notifyJalonTodayForGroup($this->usersValider, WorkflowData::STATES_ACTION_UPDATE_VALIDER);
        return $this->getMessages();
    }

    private function notifyJalonTodayForGroup(array $users, array $states)
    {
        foreach ($users as $user) {
            $this->actionSearchDto
                ->setUserWriter($user->getId())
                ->setStates($states)
                ->setJalonFrom((new \DateTime())->format('Y-m-d 00:00:00'))
                ->setJalonTo((new \DateTime())->format('Y-m-d 23:59:59'));

            /** @var Action[] $result */
            $result = $this->actionRepository->findAllForDto($this->actionSearchDto, ActionRepository::FILTRE_DTO_INIT_UNITAIRE);

            if (!empty($result)) {
                $this->addMessage(
                    Messagor::TABULTATION.
                    ' Notification à '.$user->getName().
                    ' ['.$user->getEmail().']');

                $this->sendMail->send(
                    [
                        'user' => $user,
                        'actions' => $result,
                    ],
                    SendMail::ACTION_JALON_TODAY,
                    'PAR : Liste des actions dont la date de jalon est à aujourd\'hui'
                );
            }
        }
    }
}
