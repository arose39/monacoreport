<?php declare(strict_types=1);

require_once realpath("vendor/autoload.php");

use MonacoReport\Formatters\HtmlFormatter;
use MonacoReport\Parsers\AbbreviationsParser;
use MonacoReport\Parsers\LogParser;
use MonacoReport\LapTime;
use MonacoReport\RaceInfoBuilder;
use MonacoReport\Racer;
use MonacoReport\RacersCollection;
use MonacoReport\Report;

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
$formatter = new HtmlFormatter();
$report = new Report($racersCollection, $formatter);
$report->print();

