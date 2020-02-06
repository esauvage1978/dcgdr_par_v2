<?php

namespace App\Helper;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

abstract class CommandTool extends Command
{
    const TABULTATION='      |___ ';

    /**
     * @var array
     */
    private $messages;

    protected function calculTime($fin, $debut): int
    {
        return ($fin - $debut) * 1000;
    }

    public function showMessage(OutputInterface $output)
    {
        foreach ($this->messages as $message) {
            $output->writeln($message);
        }
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function getMessagesForAlert(): string
    {
        $affichage="";
        foreach ($this->messages as $message) {
            $affichage = $affichage.'<br/>'.$message;
        }
        return $affichage;
    }

    public function addMessage(string $info)
    {
        $this->messages = array_merge(
            $this->messages,
            [$info]
        );
    }

    public function addMessages(array $infos)
    {
        foreach ($infos as $info) {
            $this->addMessage($info);
        }
    }

    public function __construct()
    {
        $this->messages = [];

        parent::__construct();
    }
}
