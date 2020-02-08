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
    ) {
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
        $dto = $this->actionSearchDto;
        $repo = $this->actionRepository;
        $debut = microtime(true);

        $dto->setState(WorkflowData::STATE_FINALISED);

        $actions = $repo->findAllForDto($dto, ActionRepository::FILTRE_DTO_INIT_TABLEAU);
        $this->addMessage(
            '# Nombre d\'actions dans l\'état '.WorkflowData::STATE_FINALISED.' : '.count($actions)
            );

        foreach ($actions as $action) {
            $actionCheck = new ActionCheck($action);
            if ($actionCheck->checkRegionStartAtBeforeOrEqualNow()) {
                $info = 'transition ('.WorkflowData::TRANSITION_TO_DEPLOYE.
                    ') : Date de début passée de '.$actionCheck->getDiffRegionStartAtAfterNow().' jours ';
                $messageBascule = 'L\'action est automatiquement déployée en raison d\'une date de début de déploiement au '.
                    $action->getRegionStartAt()->format('d/m/Y');
                $this->bascule($action, WorkflowData::TRANSITION_TO_DEPLOYE, $info, $messageBascule);
            } else {
                $this->basculeNonConcernee($action);
            }
        }

        $dto->setState(WorkflowData::STATE_DEPLOYED);

        $actions = $repo->findAllForDto($dto, ActionRepository::FILTRE_DTO_INIT_TABLEAU);

        $this->addMessage('# Nombre d\'actions dans l\'état '.WorkflowData::STATE_DEPLOYED.' : '.count($actions));

        foreach ($actions as $action) {
            $actionCheck = new ActionCheck($action);
            if ($actionCheck->checkRegionEndAtBeforeOrEqualNow()) {
                $info = 'transition ('.WorkflowData::TRANSITION_TO_MEASURED.
                    ') Date de fin passée de '.$actionCheck->getDiffRegionEndAtBeforeOrEqualNow().' jours ';
                $messageBascule = 'Fin automatique du déploiement de l\'action en raison d\'une date de fin de déploiement au '.
                    $action->getRegionEndAt()->format('d/m/Y');
                $this->bascule($action, WorkflowData::TRANSITION_TO_MEASURED, $info, $messageBascule);
            } else {
                $this->basculeNonConcernee($action);
            }
        }

        $fin = microtime(true);

        $this->addMessage('Traitement effectué en  '.$this->calculTime($fin, $debut).' millisecondes.');
    }



    private function bascule(Action $action, string $transition, string $info, string $messageBascule)
    {
        $this->addMessage(CommandTool::TABULTATION .'Action : '.$action->getId().' '.$info);
        if (!$this->workflowManager->applyTransition($action, $transition, $messageBascule, true)) {
            $this->addMessage('KO');
        }
    }

    private function basculeNonConcernee(Action $action)
    {
        $this->addMessage(CommandTool::TABULTATION .'Action : '.$action->getId().' : non concernée');
    }


}
