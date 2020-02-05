<?php

namespace App\Subscriber;

use App\Entity\Action;
use App\Entity\Deployement;
use App\Event\WorkflowTransitionEvent;
use App\Helper\SendMail;
use App\Repository\ActionRepository;
use App\Workflow\WorkflowData;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WorkflowMailerSubscriber implements EventSubscriberInterface
{
    /**
     * @var SendMail
     */
    private $sendmail;

    /**
     * @var ActionRepository
     */
    private $actionRepository;

    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    public function __construct(
        SendMail $sendmail,
        ActionRepository $actionRepository,
        ParameterBagInterface $parameterBag
    )
    {
        $this->sendmail = $sendmail;
        $this->actionRepository = $actionRepository;
        $this->parameterBag = $parameterBag;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            WorkflowTransitionEvent::NAME => 'onWorklowTransitionEvent',
        ];
    }

    public function onWorklowTransitionEvent(WorkflowTransitionEvent $event): int
    {
        /** @var Action $action */
        $action = $event->getAction();
        /** @var string $state */
        $state = $action->getState();

        $stateMailForDeployement = [
            WorkflowData::STATE_DEPLOYED,
            WorkflowData::STATE_MEASURED,
        ];

        $this->sendMailForAction($action, $state);

        if (in_array($state, $stateMailForDeployement)) {
            $this->sendMailForDeployement($action, $state);
        }
        return 0;
    }

    private function sendMailForAction(Action $action, string $state)
    {
        if (!$this->checkMailForState($state)) {
            return -1;
        }

        $validers = [
            WorkflowData::STATE_COTECH,
            WorkflowData::STATE_CODIR,
        ];

        if (in_array($state, $validers)) {
            $user = $this->getUserValider($action);
        } else {
            $user = $this->getUserWriter($action);
        }

        $datas = [
            'user' => $user,
            'action' => $action,
        ];

        return $this->sendmail->send(
            $datas,
            'workflow/' . $state,
            'DCGDR PAR : ' . WorkflowData::getTitleOfMail($state)
        );
    }

    private function sendMailForDeployement(Action $action, string $state)
    {
        if (!$this->checkMailForState($state)) {
            return -1;
        }

        foreach ($action->getDeployements() as $deployement) {

            $user =$this->getUserDeployementWriters($deployement);

            $datas = [
                'user' => $user,
                'deployement' => $deployement,
            ];

            $this->sendmail->send(
                $datas,
                'workflow/' . $state . '_unitaire',
                'DCGDR PAR : ' . WorkflowData::getTitleOfMail($state)
            );
        }
    }

    private function checkMailForState(string $state): bool
    {
        $parameter = 'mailer.workflow.' . $state;
        return $this->parameterBag->get($parameter);
    }

    public function getUserValider(Action $action)
    {
        $users = [];
        foreach ($action->getValiders() as $corbeille) {
            foreach ($corbeille->getUsers() as $user) {
                $users = array_merge([
                    $user->getEmail() => $user->getName(),
                ], $users);
            }
        }

        return $users;
    }

    public function getUserWriter(Action $action)
    {
        $users = [];
        foreach ($action->getWriters() as $corbeille) {
            foreach ($corbeille->getUsers() as $user) {
                $users = array_merge([
                    $user->getEmail() => $user->getName(),
                ], $users);
            }
        }

        return $users;
    }

    public function getUserDeployementWriters(Deployement $deployement)
    {
        $users = [];

        foreach ($deployement->getWriters() as $corbeille) {
            foreach ($corbeille->getUsers() as $user) {
                $users = array_merge([
                    $user->getEmail() => $user->getName(),
                ], $users);
            }
        }

        return $users;
    }
}
