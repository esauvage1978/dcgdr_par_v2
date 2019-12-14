<?php

namespace App\Helper;

class ToolCollecion
{
    /**
     * @var array
     */
    private $oldData;

    /**
     * @var array
     */
    private $newData;

    public function __construct(array $oldData=[],array $newData=[])
    {
        $this->oldData=$oldData;
        $this->newData=$newData;
    }

    public function getDeleteDiff()
    {
        return array_udiff_assoc(
            $this->oldData,
            $this->newData,
            function($a, $b) {return $a === $b ? 0 : 1;}
        );
    }

    public function getInsertDiff()
    {
        return array_udiff_assoc(
            $this->newData,
            $this->oldData,
            function($a, $b) {return $a === $b ? 0 : 1;}
        );
    }
}