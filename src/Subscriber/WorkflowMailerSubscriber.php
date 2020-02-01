<?php

namespace App\Subscriber;

use App\Entity\Action;
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
    ) {
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

        $parameter = 'mailer.workflow.'.$state;

        if (!$this->parameterBag->get($parameter)) {
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

        dump($datas);

        return $this->sendmail->send(
            $datas,
            'workflow.'.$state,
            'DCGDR PAR : '.WorkflowData::getTitleOfMail($state)
        );
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
}
