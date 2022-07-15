<?php


namespace MonacoReport\Formatters;


class HTMLFormatter implements FormatterInterface
{

    public function format(array $report, string $sortOrder): void
    {
        $topRacersNumber =15;
        echo '<ol>';
        $i = 0;
        $reportItemsNumber = count($report);
        while ($i != $topRacersNumber) {
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
}