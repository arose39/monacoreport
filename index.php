<?php declare(strict_types=1);

require_once realpath("vendor/autoload.php");

use MonacoReport\FilesParser;
use MonacoReport\LapTime;
use MonacoReport\Racer;
use MonacoReport\RacersCollection;
use MonacoReport\Report;

$startLogPath = 'resources/start.log';
$endLogPath = 'resources/end.log';
$abbreviationsPath = 'resources/abbreviations.txt';

$filesParser = new FilesParser($startLogPath, $endLogPath, $abbreviationsPath);
$racersRaceInfo = $filesParser->getRacersRaceInfo();
$racersCollection = new RacersCollection();
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
$report = new Report($racersCollection);
$report->printHTML();

