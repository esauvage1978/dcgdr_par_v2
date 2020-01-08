<?php

namespace App\Command;

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

class CalculTauxCommand extends Command
{
    protected static $defaultName = 'app:calcultaux';

    private $managerRegistry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->managerRegistry = $registry;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Calcul les taux de l\'application.')
            ->setHelp('Cette commande permet de lancer l\'ensemble des calculs pour les taux1 et taux 2.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln($this->calcul());
        return 0;
    }

    public function calcul(): string
    {
        $debut = microtime(true);

        $repo = new ActionRepository($this->managerRegistry);
        $repo->tauxRaz();
        $repo->tauxCalcul();

        $repo = new CategoryRepository($this->managerRegistry);
        $repo->tauxRaz();
        $repo->tauxCalcul();

        $repo = new ThematiqueRepository($this->managerRegistry);
        $repo->tauxRaz();
        $repo->tauxCalcul();

        $repo = new PoleRepository($this->managerRegistry);
        $repo->tauxRaz();
        $repo->tauxCalcul();

        $repo = new AxeRepository($this->managerRegistry);
        $repo->tauxRaz();
        $repo->tauxCalcul();



        $fin = microtime(true);

        return 'Traitement effectué en  '.$this->calculTime($fin, $debut).' millisecondes.';
    }

    private function calculTime($fin, $debut): int
    {
        return ($fin - $debut) * 1000;
    }
}
