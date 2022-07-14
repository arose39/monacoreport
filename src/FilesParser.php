<?php

namespace MonacoReport;

class FilesParser
{
     public function __construct(
        private string $startLogPath,
        private string $endLogPath,
        private string $abbreviationsPath
    ){}

    public function getRacersRaceInfo(): array
    {
        $racersInfo = $this->parseAbbreviations($this->abbreviationsPath);
        $startLog = $this->parseLogs($this->startLogPath);
        $endLog = $this->parseLogs($this->endLogPath);
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

    private function parseLogs(string $logPath): array
    {
        $logData = $this->getDataFromFiles($logPath);
        $parsedLog = [];
        $i = 0;
        foreach ($logData as $string) {
            $racersAbreviationsMathc = [];
            preg_match('/^.../', $string, $racersAbreviationsMathc);
            if (isset($racersAbreviationsMathc[0])) {
                $parsedLog[$i]["racer_abbreviation"] = $racersAbreviationsMathc[0];
            }
            $dateTimeMathc = [];
            preg_match('/([0-9]{4})-([0-9]{2})-([0-9]{2})_([0-9]{2}):([0-9]{2}):([0-9]{2})\.([0-9]{3})/',
                $string,
                $dateTimeMathc
            );
            if (isset($dateTimeMathc[0])) {
                $parsedLog[$i]["date_time"] = $dateTimeMathc[0];
            }
            $i++;
        }

        return $parsedLog;
    }

    private function parseAbbreviations(string $abbreviationsPath): array
    {
        $abbreviationsData = $this->getDataFromFiles($abbreviationsPath);
        $racersInfo = [];
        $i = 0;
        foreach ($abbreviationsData as $abbreviation) {
            $separatedString = explode("_", $abbreviation);
            $racersInfo[$i]["abbreviation"] = $separatedString[0];
            $racersInfo[$i]["name"] = $separatedString[1];
            $racersInfo[$i]["team"] = $separatedString[2];

            $i++;
        }

        return $racersInfo;
    }

    // Не использовал files_det_content(), так как удобнее считывать построчно, а не целиком
    private function getDataFromFiles(string $path): array
    {
        $fp = fopen($path, 'r');
        $data = [];
        for ($i = 0; $fileString = fgets($fp); $i++) {
            $data[$i] = $fileString;
        }
        fclose($fp);

        return $data;
    }
}