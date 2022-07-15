<?php declare(strict_types=1);


namespace MonacoReport;


class RaceInfoBuilder
{
    public function build(array $startLog, array $endLog, array $racersInfo): array
    {
        $racersRaceInfo = [];
        $i = 0;
        foreach ($racersInfo as $racerInfo) {
            $racersRaceInfo[$i]['name'] = $racerInfo['name'];
            $racersRaceInfo[$i]['team'] = $racerInfo['team'];
            $racersRaceInfo[$i]['abbreviation'] = $racerInfo['abbreviation'];
            foreach ($startLog as $racerStartTimeInfo) {
                if ($racerInfo["abbreviation"] === $racerStartTimeInfo['racer_abbreviation']) {
                    $racersRaceInfo[$i]['start_date_time'] = $racerStartTimeInfo['date_time'];
                }
            }
            foreach ($endLog as $racerEndTimeInfo) {
                if ($racerInfo["abbreviation"] === $racerEndTimeInfo['racer_abbreviation']) {
                    $racersRaceInfo[$i]['end_date_time'] = $racerEndTimeInfo['date_time'];
                }
            }
            $i++;
        }

        return $racersRaceInfo;
    }

}