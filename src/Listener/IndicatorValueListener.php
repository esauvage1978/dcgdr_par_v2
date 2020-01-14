<?php

namespace App\Listener;

use App\Command\CalculTauxCommand;
use App\Entity\IndicatorValue;
use App\Helper\IndicatorValueHistoryCreate;
use App\Manager\IndicatorValueManager;
use Doctrine\ORM\Mapping as ORM;
use Exception;

class IndicatorValueListener
{
    /**
     * @var IndicatorValueManager
     */
    private $indicatorValueManager;

    /**
     * @var CalculTauxCommand
     */
    private $calculTauxCommand;

    /**
     * @var IndicatorValueHistoryCreate
     */
    private $iVHistoryCreate;

    /**
     * IndicatorValueListener constructor.
     *
     * @param IndicatorValueManager       $indicatorValueManager
     * @param CalculTauxCommand           $calculTauxCommand
     */
    public function __construct(IndicatorValueManager $indicatorValueManager,
                                CalculTauxCommand $calculTauxCommand,
                                IndicatorValueHistoryCreate $iVHistoryCreate
    ) {
        $this->iVHistoryCreate=$iVHistoryCreate;
        $this->indicatorValueManager = $indicatorValueManager;
        $this->calculTauxCommand = $calculTauxCommand;
    }

    /**
     * @param IndicatorValue $indicatorValue
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersistHandler(IndicatorValue $indicatorValue)
    {
        $indicatorValue
            ->setTaux1(
                $this->indicatorValueManager->calculTaux($indicatorValue, true)
            )
            ->setTaux2(
                $this->indicatorValueManager->calculTaux($indicatorValue, false)
            );
    }

    /**
     * @param IndicatorValue $indicatorValue
     *
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     *
     * @throws Exception
     */
    public function postPersistHandler(IndicatorValue $indicatorValue)
    {
        $this->calculTauxCommand->calcul();

        $this->iVHistoryCreate->createHistory($indicatorValue);
    }
}
