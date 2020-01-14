<?php

namespace App\Manager;

use App\Entity\Deployement;
use App\Entity\EntityInterface;
use App\Entity\Indicator;
use App\Entity\IndicatorValue;
use App\Indicator\IndicatorData;
use App\Validator\IndicatorValueValidator;
use Doctrine\ORM\EntityManagerInterface;

class IndicatorValueManager extends ManagerAbstract
{
    /**
     * IndicatorValueManager constructor.
     */
    public function __construct(
        EntityManagerInterface $manager,
        IndicatorValueValidator $validator
    ) {
        parent::__construct($manager, $validator);
    }

    public function save(EntityInterface $entity): bool
    {
        if (!$this->validator->isValid($entity)) {
            return false;
        }

        $this->manager->persist($entity);
        $this->manager->flush();

        return true;
    }

    public function initialiseEntity(Indicator $indicator, Deployement $deployement, IndicatorValue $indicatorValue = null): IndicatorValue
    {
        if (null === $indicatorValue) {
            $indicatorValue = new IndicatorValue();

            $indicatorValue
                ->setDeployement($deployement)
                ->setIndicator($indicator)
                ->setTaux1(0)
                ->setTaux2(0)
                ->setEnable(true);

            $indicatorValue->setGoal(
                $this->initialiseGoal($indicator)
            );
            $indicatorValue->setValue(
                $this->initialiseValue($indicator)
            );
        } else {
            $indicatorValue->setEnable(!$indicatorValue->getEnable());
        }

        $indicatorValue->setTaux1($this->calculTaux($indicatorValue, true));
        $indicatorValue->setTaux2($this->calculTaux($indicatorValue, false));

        return $indicatorValue;
    }

    private function initialiseGoal(Indicator $indicator)
    {
        $keepGoal = [
            IndicatorData::QUANTITATIF,
        ];

        if (in_array($indicator->getIndicatortype(), $keepGoal)) {
            return $indicator->getGoal();
        } else {
            return '100';
        }
    }

    private function initialiseValue(Indicator $indicator)
    {
        $keepValue = [
            IndicatorData::QUANTITATIF,
            IndicatorData::QUALITATIF,
        ];

        if (in_array($indicator->getIndicatortype(), $keepValue)) {
            return $indicator->getValue();
        } else {
            return '';
        }
    }

    public function calculTaux(IndicatorValue $indicatorValue, $taux1 = true)
    {
        $taux = 0;

        $calculTauxUnitaire = [
            'qualitatif',
            'qualitatif_seuil_5',
            'qualitatif_seuil_25',
            'quantitatif',
            'quantitatif_goal',
        ];

        if (in_array($indicatorValue->getIndicator()->getIndicatortype(), $calculTauxUnitaire)) {
            $taux = $this->calculTauxUnitaire(
                $indicatorValue->getGoal(),
                $indicatorValue->getValue(),
                $taux1
            );
        } elseif ('binaire_oui' === $indicatorValue->getIndicator()->getIndicatortype()) {
            $taux = $this->calculTauxBinaire(
                $indicatorValue->getValue(),
                ['oui']
            );
        } elseif ('binaire_non' === $indicatorValue->getIndicator()->getIndicatortype()) {
            $taux = $this->calculTauxBinaire(
                $indicatorValue->getValue(),
                ['non']
            );
        } elseif ('binaire' === $indicatorValue->getIndicator()->getIndicatortype()) {
            $taux = $this->calculTauxBinaire(
                $indicatorValue->getValue(),
                ['non', 'oui']
            );
        }

        return $taux;
    }

    private function calculTauxUnitaire($total, $nombre, $limiteA100 = true)
    {
        try {
            $total = preg_replace('`[^0-9]`', '', $total);
            $nombre = preg_replace('`[^0-9]`', '', $nombre);

            if ('0' == $total) {
                return 0;
            }

            $pourcentage = 100;
            $resultat = round(($nombre / $total) * $pourcentage);

            if ($limiteA100 and $resultat > 100) {
                return 100;
            } else {
                return $resultat;
            }
        } catch (\Exception $e) {
            dump($e.' - '.$total.' - '.$nombre);
        }
    }

    private function calculTauxBinaire($valeur, $listeValeurGoal)
    {
        if (in_array($valeur, $listeValeurGoal)) {
            return 100;
        } else {
            return 0;
        }
    }
}
