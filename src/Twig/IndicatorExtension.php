<?php


namespace App\Twig;


use App\Indicator\IndicatorData;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class IndicatorExtension extends AbstractExtension
{

    public function __construct()
    {
    }

    public function getFilters()
    {
        return [
            new TwigFilter('indicatorGetNameOfIndicator', [$this, 'indicatorGetNameOfIndicator']),
        ];
    }

    public function indicatorGetNameOfIndicator(string $state)
    {
        return IndicatorData::getNameOfIndicator($state);
    }
}