<?php

namespace App\Manager;

use App\Entity\Deployement;
use App\Entity\EntityInterface;
use App\Entity\Indicator;
use App\Entity\IndicatorValue;
use App\Indicator\IndicatorData;
use App\Repository\IndicatorValueRepository;
use App\Validator\IndicatorValueValidator;
use Doctrine\ORM\EntityManagerInterface;

class IndicatorValueManager extends ManagerAbstract
{
    /**
     * @var IndicatorValueRepository
     */
    private $repo;

    /**
     * IndicatorValueManager constructor.
     */
    public function __construct(
        EntityManagerInterface $manager,
        IndicatorValueValidator $validator,
        IndicatorValueRepository $repo
    ) {
        parent::__construct($manager, $validator);
        $this->repo = $repo;
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

    public function initialiseEntity(
        Indicator $indicator,
        Deployement $deployement,
        IndicatorValue $indicatorValue = null): IndicatorValue
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
            IndicatorData::QUANTITATIF_GOAL,
            IndicatorData::CONTRIBUTIF,
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
            IndicatorData::QUANTITATIF_GOAL,
            IndicatorData::QUALITATIF,
            IndicatorData::QUALITATIF_PALIER_5,
            IndicatorData::QUALITATIF_PALIER_25,
            IndicatorData::CONTRIBUTIF,
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
            IndicatorData::QUALITATIF,
            IndicatorData::QUALITATIF_PALIER_5,
            IndicatorData::QUALITATIF_PALIER_25,
            IndicatorData::QUANTITATIF,
        ];

        if (in_array($indicatorValue->getIndicator()->getIndicatortype(), $calculTauxUnitaire)) {
            return $this->calculTauxUnitaire(
                $indicatorValue->getGoal(),
                $indicatorValue->getValue(),
                $taux1
            );
        }

        switch ($indicatorValue->getIndicator()->getIndicatortype()) {
            case IndicatorData::CONTRIBUTIF:
                $taux = $this->calculTauxUnitaire(
                    $indicatorValue->getIndicator()->getGoal(),
                    $this->getSumValue($indicatorValue->getIndicator()),
                    $taux1
                );
                $this->repo->initialiseTaux($indicatorValue->getIndicator()->getId(), $taux1, $taux);
                break;
            case IndicatorData::QUANTITATIF_GOAL:
                $taux = $this->calculTauxUnitaire(
                    $indicatorValue->getIndicator()->getGoal(),
                    $indicatorValue->getValue(),
                    $taux1
                );
                break;
            case IndicatorData::BINAIRE_OUI:
                $taux = $this->calculTauxBinaire(
                    $indicatorValue->getValue(),
                    ['oui']
                );
                break;
            case IndicatorData::BINAIRE_NON:
                $taux = $this->calculTauxBinaire(
                    $indicatorValue->getValue(),
                    ['non']
                );
                break;

            case IndicatorData::BINAIRE:
                $taux = $this->calculTauxBinaire(
                    $indicatorValue->getValue(),
                    ['non', 'oui']
                );
                break;
        }

        return $taux;
    }

    private function getSumValue(Indicator $indicator)
    {
        $cumul = 0;
        foreach ($indicator->getIndicatorValues() as $indicatorValue) {
            $cumul += $indicatorValue->getValue();
        }

        return $cumul;
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
