<?php


namespace App\Helper;


class Messagor
{
    const TABULTATION='      |___ ';
    const SECTION='### ';
    /**
     * @var array
     */
    private $messages;

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function addMessage(string $info)
    {
        $this->messages = array_merge(
            $this->messages,
            [$info]
        );
    }

    public function __construct()
    {
        $this->messages = [];
    }
}