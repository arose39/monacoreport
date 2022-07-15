<?php declare(strict_types=1);


namespace MonacoReport\Commands;

use MonacoReport\Formatters\ConsoleFormatter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use MonacoReport\Parsers\AbbreviationsParser;
use MonacoReport\Parsers\LogParser;
use MonacoReport\LapTime;
use MonacoReport\RaceInfoBuilder;
use MonacoReport\Racer;
use MonacoReport\RacersCollection;
use MonacoReport\Report;
use Symfony\Component\Console\Style\SymfonyStyle;

class ShowReportCommand extends Command
{
    protected function configure()
    {
        $this->setName('show-report')
            ->setDescription('Shows report of Monaco 2018 Racing')
            ->addOption(
                'sort_order',
                's',
                InputOption::VALUE_REQUIRED,
                'Іets the sort order for the displayed data,
              use DESC for descending order or ASC fo ascending order',
                "ASC"
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startLogPath = 'resources/start.log';
        $endLogPath = 'resources/end.log';
        $abbreviationsPath = 'resources/abbreviations.txt';

        $logParser = new LogParser();
        $abbreviationsParser = new AbbreviationsParser();
        $raceInfoBuilder = new RaceInfoBuilder();
        $racersCollection = new RacersCollection();

        $startLog = $logParser->parse($startLogPath);
        $endLog = $logParser->parse($endLogPath);
        $racersInfo = $abbreviationsParser->parse($abbreviationsPath);
        $racersRaceInfo = $raceInfoBuilder->build($startLog, $endLog, $racersInfo);

        foreach ($racersRaceInfo as $racerRaceInfo) {
            $racersCollection->add(
                new Racer(
                    $racerRaceInfo['abbreviation'],
                    $racerRaceInfo['name'],
                    $racerRaceInfo['team'],
                    new LapTime($racerRaceInfo['start_date_time'], $racerRaceInfo['end_date_time'])
                )
            );
        }
        $formatter = new ConsoleFormatter($input, $output);
        $report = new Report($racersCollection, $formatter);
        $sortOrder = $input->getOption('sort_order');
        $report->print($sortOrder);

        return self::SUCCESS;
    }
}