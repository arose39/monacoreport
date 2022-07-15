<?php


namespace MonacoReport\Formatters;


use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConsoleFormatter implements FormatterInterface
{
    private InputInterface $input;
    private OutputInterface $output;

    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    public function format(array $report, string $sortOrder): void
    {
        $topRacersNumber = 15;
        $reportItemsNumber = count($report);
        $topRacersReport = [];
        $i = 0;
        while ($i <= $topRacersNumber) {
            $topRacersReport[$i]['number'] = $i;
            $topRacersReport[$i]['name'] = $report[$i]['name'];
            $topRacersReport[$i]['team'] = $report[$i]['team'];
            $topRacersReport[$i]['lap_time'] = $report[$i]['lap_time'];
            $i++;
        }

        $lastRacersReport = [];
        while ($i != $reportItemsNumber) {
            $lastRacersReport[$i]['number'] = $i;
            $lastRacersReport[$i]['name'] = $report[$i]['name'];
            $lastRacersReport[$i]['team'] = $report[$i]['team'];
            $lastRacersReport[$i]['lap_time'] = $report[$i]['lap_time'];
            $i++;
        }

        $io = new SymfonyStyle($this->input, $this->output);
        $io->title("Report of Monaco 2018 Racing");

        if ($sortOrder === "ASC") {
            $io->section('Top 15 score');
        } else {
            $io->section('Last 15 score');
        }
        $io->table(["№", 'name', 'team', 'lap_time'], $topRacersReport);

        if ($sortOrder === "ASC") {
            $io->section('Other');
        } else {
            $io->section('Top scores');
        }
        $io->table(["№", 'name', 'team', 'lap_time'], $lastRacersReport);
    }
}