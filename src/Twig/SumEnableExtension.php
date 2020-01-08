<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class SumEnableExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('sumEnable', [$this, 'sumEnable']),
        ];
    }

    public function sumEnable($entitys = [])
    {
        $nbr = 0;
        foreach ($entitys as $entity) {
            if (true == $entity->getEnable()) {
                $nbr = $nbr + 1;
            }
        }

        return $nbr;
    }
}
