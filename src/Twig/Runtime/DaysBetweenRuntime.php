<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class DaysBetweenRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function getDaysBetween(?\DateTimeInterface $start, ?\DateTimeInterface $end): int
    {
        if (!$start || !$end) {
            return 0;
        }

        return $start->diff($end)->days;
    }
}
