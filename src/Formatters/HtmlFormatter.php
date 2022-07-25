<?php declare(strict_types=1);

namespace MonacoReport\Formatters;

class HtmlFormatter implements FormatterInterface
{
    private $numberOfTopRacers = 15;

    public function format(array $report, string $sortOrder): void
    {

        echo '<ol>';
        $i = 0;
        $reportItemsNumber = count($report);
        while ($i != $this->numberOfTopRacers) {
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
