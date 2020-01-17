<?php

namespace App\Command;

use App\Helper\DeployementJalonNotificator;
use App\Repository\ActionRepository;
use App\Repository\AxeRepository;
use App\Repository\CategoryRepository;
use App\Repository\DeployementRepository;
use App\Repository\IndicatorRepository;
use App\Repository\PoleRepository;
use App\Repository\ThematiqueRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NotificatorCommand extends Command
{
    protected static $defaultName = 'app:notificator';

    private $deployementJalonNotificator;

    public function __construct(
        DeployementJalonNotificator $deployementJalonNotificator)
    {
        $this->deployementJalonNotificator = $deployementJalonNotificator;
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
        $output->writeln($this->notify());
        return 0;
    }

    public function notify(): string
    {
        $debut = microtime(true);

        $this->deployementJalonNotificator->notifyJalonToday();

        $fin = microtime(true);

        return 'Traitement effectué en  '.$this->calculTime($fin, $debut).' millisecondes.';
    }

    private function calculTime($fin, $debut): int
    {
        return ($fin - $debut) * 1000;
    }
}
