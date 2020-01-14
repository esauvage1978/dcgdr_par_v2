<?php

namespace App\Listener;

use App\Entity\Indicator;
use Doctrine\ORM\Mapping as ORM;

class IndicatorListener
{
    public function __construct()
    {
    }

    /**
     * @param Indicator $indicator
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersistHandler(Indicator $indicator)
    {
        if (null === $indicator->getGoal()) {
            $indicator->setGoal(100);
        }
        if (null === $indicator->getValue()) {
            $indicator->setGoal(0);
        }
    }
}
