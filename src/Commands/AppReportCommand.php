<?php declare(strict_types=1);

namespace MonacoReport\Commands;

use http\Exception\InvalidArgumentException;
use MonacoReport\Formatters\ConsoleFormatter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
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

class AppReportCommand extends Command
{
    protected function configure()
    {
        $this->setName('app:report')
            ->setDescription('Shows report of Monaco 2018 Racing')
            ->addOption(
                'files',
                null,
                InputOption::VALUE_REQUIRED,
                'Print folder name with logs and abbreviations files',
                null
            )
            ->addOption(
                'sort_order',
                's',
                InputOption::VALUE_REQUIRED,
                'Sets the sort order for the displayed data,
              use DESC for descending order or ASC fo ascending order',
                "ASC"
            )
            ->addOption(
                'driver',
                null,
                InputOption::VALUE_REQUIRED,
                'Show driver info'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $folderPath = $input->getOption('files');
        if (!$folderPath) {
            $output->writeln("Укажите путь к папке с фалами (app:report --files folder_path)");
        } else {
            $startLogPath = "$folderPath/start.log";
            $endLogPath = "$folderPath/end.log";
            $abbreviationsPath = "$folderPath/abbreviations.txt";

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

            if ($driver = $input->getOption('driver')) {
                $output->writeln($this->getRacerInfo($driver, $racersCollection));
                return self::SUCCESS;
            }

            $formatter = new ConsoleFormatter($input, $output);
            $report = new Report($racersCollection, $formatter);
            $sortOrder = $input->getOption('sort_order');
            $report->print($sortOrder);
        }

        return self::SUCCESS;
    }

    private function getRacerInfo(string $driver, RacersCollection $racersCollection): array
    {
        $driverInfo = [];
        foreach ($racersCollection as $racer) {
            if ($racer->getName() === $driver) {
                $driverInfo['name'] = $racer->getName();
                $driverInfo['team'] = $racer->getTeam();
                $driverInfo['lap_time'] = $racer->getLapTime();
            }
        }
        if (!isset($driverInfo['name'])) {
            return ["There isn't driver with name $driver in current report"];
        }

        return $driverInfo;
    }
}
