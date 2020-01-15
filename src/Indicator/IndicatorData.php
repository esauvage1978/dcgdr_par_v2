<?php

namespace App\Indicator;

class IndicatorData
{
    const QUALITATIF = 'qualitatif';
    const QUALITATIF_PALIER_5 = 'qualitatif_palier_5';
    const QUALITATIF_PALIER_25 = 'qualitatif_palier_25';
    const QUANTITATIF = 'quantitatif';
    const QUANTITATIF_GOAL = 'quantitatif_goal';
    const CONTRIBUTIF = 'contributif';
    const BINAIRE = 'binaire';
    const BINAIRE_OUI = 'binaire_oui';
    const BINAIRE_NON = 'binaire_non';

    public static function getNameOfIndicator(string $state)
    {
        $stateName = '';
        switch ($state) {
            case self::CONTRIBUTIF:
                $stateName = 'Indicateur contributif régional';
                break;
            case self::QUALITATIF:
                $stateName = 'Indicateur qualitatif (palier 10)';
                break;
            case self::QUALITATIF_PALIER_5:
                $stateName = 'Indicateur qualitatif (palier 5)';
                break;
            case self::QUALITATIF_PALIER_25:
                $stateName = 'Indicateur qualitatif (palier 25)';
                break;
            case self::QUANTITATIF:
                $stateName = 'Indicateur quantitatif';
                break;
            case self::QUANTITATIF_GOAL:
                $stateName = 'Indicateur quantitatif (objectif figé)';
                break;
            case self::BINAIRE:
                $stateName = 'Indicateur binaire';
                break;
            case self::BINAIRE_OUI:
                $stateName = 'Indicateur binaire (oui)';
                break;
            case self::BINAIRE_NON:
                $stateName = 'Indicateur binaire (non)';
                break;
        }

        return $stateName;
    }

    public static function getFullNameOfIndicator(string $state)
    {
        $stateName = '';
        switch ($state) {
            case self::CONTRIBUTIF:
                $stateName = 'Indicateur contributif régional : Les organismes contribuent collectivement à l\'atteinte de l\'objectif' ;
                break;
            case self::QUALITATIF:
                $stateName = 'Indicateur qualitatif : de 0% à 100% avec des paliers de 10';
                break;
            case self::QUALITATIF_PALIER_5:
                $stateName = 'Indicateur qualitatif : de 0% à 100% avec des paliers de 5';
                break;
            case self::QUALITATIF_PALIER_25:
                $stateName = 'Indicateur qualitatif : de 0% à 100% avec des paliers de 25';
                break;
            case self::QUANTITATIF:
                $stateName = 'Indicateur quantitatif : ce type d\'indicateur permet la sur performance des organismes';
                break;
            case self::QUANTITATIF_GOAL:
                $stateName = 'Indicateur quantitatif : L\'objectif est verrouillé. Ce type d\'indicateur permet la sur performance des organismes';
                break;
            case self::BINAIRE:
                $stateName = 'Indicateur binaire : Oui/Non, le taux sera de 100% si un choix est effectué';
                break;
            case self::BINAIRE_OUI:
                $stateName = 'Indicateur binaire (oui) : Oui/Non, le taux sera de 100% si le choix "oui" est effectué';
                break;
            case self::BINAIRE_NON:
                $stateName = 'Indicateur binaire (non) : Oui/Non, le taux sera de 100% si le choix "non" est effectué';
                break;
        }

        return $stateName;
    }
}
