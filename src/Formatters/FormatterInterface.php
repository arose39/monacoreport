<?php

namespace MonacoReport\Formatters;

interface FormatterInterface
{
    public function format(array $report,string $sortOrder): void;
}