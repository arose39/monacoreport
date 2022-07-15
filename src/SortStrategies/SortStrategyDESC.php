<?php declare(strict_types=1);

namespace MonacoReport\SortStrategies;

class SortStrategyDESC implements SortStrategyInterface
{
    public function execute(array $report): array
    {
        usort($report, function ($a, $b) {
            return ($a['lap_time'] > $b['lap_time']) ? -1 : 1;
        });

        return $report;
    }
}
