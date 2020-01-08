<?php

namespace App\Indicator;

class IndicatorData
{
    const QUALITATIF='qualitatif';
    const QUANTITATIF='quantitatif';

    public static function getNameOfIndicator(string $state)
    {
        $stateName = '';
        switch ($state) {
            case self::QUALITATIF:
                $stateName = 'Indicateur qualitatif';
                break;
            case self::QUANTITATIF:
                $stateName = 'Indicateur quantitatif';
                break;
        }

        return $stateName;
    }

    public static function getFullNameOfIndicator(string $state)
    {
        $stateName = '';
        switch ($state) {
            case self::QUALITATIF:
                $stateName = 'Indicateur qualitatif : de 0% à 100% avec des paliers de 10';
                break;
            case self::QUANTITATIF:
                $stateName = 'Indicateur quantitatif : ce type d\'indicateur permet la sur performance des organismes';
                break;
        }

        return $stateName;
    }
}