<?php

namespace MonacoReport;

use http\Exception\InvalidArgumentException;
use MonacoReport\RacersCollection;

class Report
{
    private RacersCollection $racersCollection;

    public function __construct(RacersCollection $racersCollection)
    {
        $this->racersCollection = $racersCollection;
    }

    public function build(string $sortOrder)
    {
        $report = [];
        $i = 0;
        foreach ($this->racersCollection as $racer) {
            $report[$i]['name'] = $racer->getName();
            $report[$i]['team'] = $racer->getTeam();
            $report[$i]['lap_time'] = $racer->getLapTime();
            $i++;
        }
        if ($sortOrder == "ASC") {
            usort($report, function ($a, $b) {
                return ($a['lap_time'] < $b['lap_time']) ? -1 : 1;
            });
        } elseif ($sortOrder == "DESC") {
            usort($report, function ($a, $b) {
                return ($a['lap_time'] > $b['lap_time']) ? -1 : 1;
            });
        } else {
            throw new InvalidArgumentException("Uncorrect argument for sorting, use 'DESC' or 'ASC'");
        }

        return $report;
    }

    public function printHTML(string $sortOrder = "ASC")
    {
        $report = $this->build($sortOrder);
        echo '<ol>';
        $i = 0;
        $reportItemsNumber = count($report);
        while ($i != 15) {
            echo "<li>" . $report[$i]['name'] . '      | ' . $report[$i]['team'] . '     | ' . $report[$i]['lap_time'] . "</li>";
            $i++;
        }
        echo '<hr>';
        while ($i != $reportItemsNumber) {
            echo "<li>" . $report[$i]['name'] . '      | ' . $report[$i]['team'] . '     | ' . $report[$i]['lap_time'] . "</li>";
            $i++;
        }
        echo '</ol>';
    }

    public function printConsole(string $sortOrder = "ASC")
    {
        // Здесь будет реализация отрисовки для консольного интерфейса
    }
}