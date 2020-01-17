<?php

namespace App\Helper;

use App\Dto\DeployementSearchDto;
use App\Entity\Deployement;
use App\Repository\DeployementRepository;
use App\Repository\UserRepository;

class DeployementJalonNotificator
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
    }

    public function notifyJalonToday()
    {
        foreach ($this->users as $user) {
            $this->deployementSearchDto
                ->setUserWriter($user->getId())
                ->setJalonOperator('=')
                ->setJalonFrom((new \DateTime())->format('Y-m-d'));

            /** @var Deployement[] $result */
            $result = $this->deployementRepository->findAllForDto($this->deployementSearchDto);

            if (!empty($result)) {
                $this->sendMail->send(
                    [
                        'user'=>$user,
                        'deployements'=>$result
                    ],
                    SendMail::DEPLOYEMENT_JALON_TODAY,
                    'PAR : Liste des déploiements à traiter'
                );

            }
        }
    }
}
