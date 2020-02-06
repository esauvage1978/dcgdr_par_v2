<?php

namespace App\Helper;

use Symfony\Component\Console\Output\OutputInterface;

interface CommandInterface
{

    public function showMessage(OutputInterface $output);

    public function getMessages(): array;

    public function addMessage(string $info);

    public function runTraitement():void;

}