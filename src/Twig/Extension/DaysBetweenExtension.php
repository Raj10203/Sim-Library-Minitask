<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\DaysBetweenRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class DaysBetweenExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('days_between', [DaysBetweenRuntime::class, 'getDaysBetween']),
        ];
    }
}
