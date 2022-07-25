<?php declare(strict_types=1);

namespace MonacoReport\Formatters;

interface FormatterInterface
{
    public function format(array $report, string $sortOrder): void;
}
