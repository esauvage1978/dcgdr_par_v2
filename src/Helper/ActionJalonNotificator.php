<?php

namespace App\Helper;

use App\Dto\ActionSearchDto;
use App\Entity\Action;
use App\Repository\ActionRepository;
use App\Repository\UserRepository;

class ActionJalonNotificator extends Messagor
{
    /**
     * @var mixed
     */
    private $users;

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
        $this->users = $userRepository->findAllWriterForAction();
        $this->actionSearchDto = $actionSearchDto;
        $this->actionRepository = $actionRepository;
        $this->sendMail=$sendMail;

        parent::__construct();
    }

    public function notifyJalonToday()
    {
        foreach ($this->users as $user) {
            $this->actionSearchDto
                ->setUserWriter($user->getId())
                ->setJalonFrom((new \DateTime())->format('Y-m-d 00:00:00'))
                ->setJalonTo((new \DateTime())->format('Y-m-d 23:59:59'));

            /** @var Action[] $result */
            $result = $this->actionRepository->findAllForDto($this->actionSearchDto, ActionRepository::FILTRE_DTO_INIT_UNITAIRE);

            if (!empty($result)) {
                $this->addMessage(
                    Messagor::TABULTATION.
                    ' Notification à '. $user->getName() .
                    ' [' .  $user->getEmail() . ']');

                $this->sendMail->send(
                    [
                        'user'=>$user,
                        'actions'=>$result
                    ],
                    SendMail::ACTION_JALON_TODAY,
                    'PAR : Liste des actions dont la date de jalon est à aujourd\'hui'
                );

            }

        }
        return $this->getMessages();
    }
}
