<?php declare(strict_types=1);

namespace MonacoReport;

use DateTime;

class LapTime
{
    private string $startDateTime;
    private string $endDateTime;
    private string $lapTime;

    public function __construct(string $startDateTime, string $endDateTime)
    {
        $this->startDateTime = $startDateTime;
        $this->endDateTime = $endDateTime;
        $this->lapTime = $this->count($startDateTime, $endDateTime);
    }

    public function getStartDateTime(): string
    {
        return $this->startDateTime;
    }

    public function getEndDateTime(): string
    {
        return $this->endDateTime;
    }

    public function getLapTime(): string
    {
        return $this->lapTime;
    }

    public function count(string $startDateTime, string $endDateTime): string
    {
        $start = DateTime::createFromFormat('Y-m-d_H:i:s.u', $startDateTime);
        $finish = DateTime::createFromFormat('Y-m-d_H:i:s.u', $endDateTime);
        $startTimestamp = $start->getTimestamp() . '.' . $start->format('u');
        $finishTimestamp = $finish->getTimestamp() . '.' . $finish->format('u');
        $resultTimestamp = round((float)($finishTimestamp - $startTimestamp), 3);
        [$RoughTime, $milliseconds] = explode(".", "$resultTimestamp");
        $timeResult = gmdate("H:i:s", (int)$RoughTime) . '.' . $milliseconds;

        return $timeResult;
    }
}
