<?php

namespace App\Command;

use App\Helper\ActionJalonNotificator;
use App\Helper\CommandInterface;
use App\Helper\CommandTool;
use App\Helper\DeployementJalonNotificator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NotificatorCommand extends CommandTool implements CommandInterface
{
    protected static $defaultName = 'app:notificator';

    private $deployementJalonNotificator;
    private $actionJalonNotificator;

    public function __construct(
        DeployementJalonNotificator $deployementJalonNotificator,
        ActionJalonNotificator $actiontJalonNotificator
    ) {
        $this->deployementJalonNotificator = $deployementJalonNotificator;
        $this->actionJalonNotificator = $actiontJalonNotificator;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Effectue les notifications quotidiennes.')
            ->setHelp('Cette commande permet de lancer toutes les notifications du site.');
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

        $this->addMessage('Lancement des Notifications pour les actions ');
        $this->addMessages($this->actionJalonNotificator->notifyJalonToday());

        $this->addMessage('Lancement des Notifications pour les déploiements ');
        $this->addMessages($this->deployementJalonNotificator->notifyJalonToday());

        $fin = microtime(true);

        $this->addMessage('Traitement effectué en  '.$this->calculTime($fin, $debut).' millisecondes.');
    }
}
