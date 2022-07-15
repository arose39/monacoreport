<?php declare(strict_types=1);

namespace MonacoReport\SortStrategies;

interface SortStrategyInterface
{
    public function execute(array $report): array;
}
