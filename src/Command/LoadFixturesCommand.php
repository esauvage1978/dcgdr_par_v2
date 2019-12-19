<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadFixturesCommand extends Command
{
    protected static $defaultName = 'app:loadfixtures';

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('load les fixtures.')
            ->setHelp('Cette commande peremet d\'afficher des occurences de la suite de fiboncci.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln($this->calcul());

        return 0;
    }

    public function calcul(): string
    {
        $debut = microtime(true);

        $this->loadFixtures('1000', false);
        $this->loadFixtures('1010');
        $this->loadFixtures('1020');
        $this->loadFixtures('1030');
        $this->loadFixtures('1040');
        $this->loadFixtures('1050');
        $this->loadFixtures('1060');
        $this->loadFixtures('1070');
        $this->loadFixtures('1080');
        $this->loadFixtures('1090');

        $fin = microtime(true);

        return 'Traitement effectué en  '.$this->calculTime($fin, $debut).' millisecondes.';
    }

    private function loadFixtures(string $groups, bool $append = true)
    {
        $command = 'php '.dirname(__DIR__, 2).
        '/bin/console doctrine:fixtures:load --group=step'.
        $groups.' '. ($append ? ' --append ' : '') .' -n ';
        passthru($command);
    }

    private function calculTime($fin, $debut): int
    {
        return ($fin - $debut) * 1000;
    }
}
