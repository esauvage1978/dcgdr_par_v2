<?php

namespace App\Command;

use App\Dto\ActionSearchDto;
use App\Entity\Action;
use App\Helper\CommandInterface;
use App\Helper\CommandTool;
use App\Repository\ActionRepository;
use App\Workflow\ActionCheck;
use App\Workflow\WorkflowActionManager;
use App\Workflow\WorkflowData;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WorkflowCommand extends CommandTool implements CommandInterface
{
    protected static $defaultName = 'app:workflow';

    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * @var ActionRepository
     */
    private $actionRepository;

    /**
     * @var WorkflowActionManager
     */
    private $workflowManager;

    /**
     * @var ActionSearchDto
     */
    private $actionSearchDto;

    public function __construct(
        ManagerRegistry $registry,
        ActionRepository $actionRepository,
        WorkflowActionManager $workflowManager,
        ActionSearchDto $actionSearchDto
    )
    {
        $this->managerRegistry = $registry;
        $this->actionRepository = $actionRepository;
        $this->workflowManager = $workflowManager;
        $this->actionSearchDto = $actionSearchDto;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Lance les traitements workflow de l\'application.')
            ->setHelp('Cette commande permet de lancer l\'ensemble des actions du workflow.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->runTraitement();
        $this->showMessage($output);

        return 0;
    }

    public function runTraitement(): void
    {

        $debut = microtime(true);

        $this->checkState(WorkflowData::TRANSITION_TO_DEPLOYE);
        $this->checkState(WorkflowData::TRANSITION_TO_MEASURED);

        $this->checkState(WorkflowData::TRANSITION_UN_DEPLOYED);
        $this->checkState(WorkflowData::TRANSITION_UN_MEASURED);

        $fin = microtime(true);

        $this->addMessage('Traitement effectué en  ' . $this->calculTime($fin, $debut) . ' millisecondes.');
    }

    private function checkState(string $transitionTo)
    {
        $dto = $this->actionSearchDto;
        $repo = $this->actionRepository;

        switch ($transitionTo) {
            case WorkflowData::TRANSITION_TO_DEPLOYE:
                $stateFrom = WorkflowData::STATE_FINALISED;
                break;
            case WorkflowData::TRANSITION_UN_DEPLOYED:
                $stateFrom = WorkflowData::STATE_DEPLOYED;
                break;
            case WorkflowData::TRANSITION_TO_MEASURED:
                $stateFrom = WorkflowData::STATE_DEPLOYED;
                break;
            case WorkflowData::TRANSITION_UN_MEASURED:
                $stateFrom = WorkflowData::STATE_MEASURED;
                break;
        }
        $dto->setState($stateFrom);

        $actions = $repo->findAllForDto($dto, ActionRepository::FILTRE_DTO_INIT_TABLEAU);
        $this->addMessage(
            '# ' . count($actions) . ' actions dans l\'état ' . $stateFrom . ' pour la transition ' . $transitionTo
        );

        $actionNonConcerne = 0;

        foreach ($actions as $action) {
            $actionCheck = new ActionCheck($action);
            $check = false;

            switch ($transitionTo) {
                case WorkflowData::TRANSITION_TO_DEPLOYE:
                    $check = $actionCheck->checkRegionStartAtBeforeOrEqualNow()
                    && $actionCheck->checkRegionEndAtAfterNow();
                    $info = 'transition (' . $transitionTo .
                        ') Date de début passée de ' . $actionCheck->getDiffRegionStartAtAfterNow() . ' jours ';
                    $messageBascule = 'Début automatique de déploiement, déploiement du ' .
                        $action->getRegionStartAt()->format('d/m/Y')
                    .' au  '  .$action->getRegionEndAt()->format('d/m/Y');
                    break;
                case WorkflowData::TRANSITION_TO_MEASURED:
                    $check =  $actionCheck->checkRegionEndAtBeforeOrEqualNow();
                    $info = 'transition (' . $transitionTo .
                        ') Date de Fin passée de ' . $actionCheck->getDiffRegionEndAtBeforeOrEqualNow() . ' jours ';
                    $messageBascule = 'Fin automatique de déploiement, déploiement du ' .
                        $action->getRegionStartAt()->format('d/m/Y')
                        .' au  '  .$action->getRegionEndAt()->format('d/m/Y');
                    break;
                case WorkflowData::TRANSITION_UN_DEPLOYED:
                    $check =  $actionCheck->checkRegionStartAtAfterNow();
                    $info = 'transition (' . $transitionTo .
                        ') Date de début au delà de ' . $actionCheck->getDiffRegionStartAtAfterNow() . ' jours ';
                    $messageBascule = 'Fin automatique de déploiement, déploiement du ' .
                        $action->getRegionStartAt()->format('d/m/Y')
                        .' au  '  .$action->getRegionEndAt()->format('d/m/Y');
                    break;
                case WorkflowData::TRANSITION_UN_MEASURED:
                    $check =  $actionCheck->checkRegionEndAtAfterNow();
                    $info = 'transition (' . $transitionTo .
                        ') Date de fin au delà de ' . $actionCheck->getDiffRegionStartAtAfterNow() . ' jours ';
                    $messageBascule = 'remise automatique de déploiement, déploiement du ' .
                        $action->getRegionStartAt()->format('d/m/Y')
                        .' au  '  .$action->getRegionEndAt()->format('d/m/Y');
                    break;

            }

            if ($check) {
                $this->bascule($action, $transitionTo, $info, $messageBascule);
            } else {
                $actionNonConcerne = $actionNonConcerne + 1;
            }
        }
        $this->basculeNonConcernee($actionNonConcerne);

    }


    private function bascule(Action $action, string $transition, string $info, string $messageBascule)
    {
        $this->addMessage(CommandTool::TABULTATION . 'Action : ' . $action->getId() . ' ' . $info);
        if (!$this->workflowManager->applyTransition($action, $transition, $messageBascule, true)) {
            $this->addMessage('KO de ' . $action->getState() . ' vers ' . $transition);
        }
    }

    private function basculeNonConcernee(int $nbr)
    {
        $this->addMessage(CommandTool::TABULTATION . $nbr . ' Actions non concernées');
    }


}
