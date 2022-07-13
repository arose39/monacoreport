<?php


//$collection = \Easy\Collections\Collection();
//
//class Person
//{
//
//    private $name;
//    private $age;
//
//    public function __construct($name, $age)
//    {
//        $this->name = $name;
//        $this->age = $age;
//    }
//
//    public function getName()
//    {
//        return $this->name;
//    }
//
//    public function setName($name)
//    {
//        $this->name = $name;
//    }
//
//    public function getAge()
//    {
//        return $this->age;
//    }
//
//    public function setAge($age)
//    {
//        $this->age = $age;
//    }
//
//}

function getDataFromFiles(string $path): array
{
    $fp = fopen($path, 'r');
    $data = [];
    for ($i = 0; $fileString = fgets($fp); $i++) {
        $data[$i] = $fileString;
    }
    fclose($fp);

    return $data;
}
$startLogPath ='resources/start.log';
$endLogPath ='resources/end.log';
$abbrevitionsPath ='resources/abbreviations.txt';

$startLogData = getDataFromFiles($startLogPath);
$endLogData = getDataFromFiles($endLogPath);
$abbreviationsData = getDataFromFiles($abbrevitionsPath);

function parseLogs(array $log): array
{
    $parsedLog = [];
    $i = 0;
    foreach ($log as $string) {
        $racersAbreviationsMathc = [];
        preg_match('/^.../', $string, $racersAbreviationsMathc);
        $parsedLog[$i]["racer_abbreviation"] = $racersAbreviationsMathc[0];

        $dateMathc = [];
        preg_match('/([0-9]{4})-([0-9]{2})-([0-9]{2})/', $string, $dateMathc);
        $parsedLog[$i]["date"] = $dateMathc[0];

        $timeMathc = [];
        preg_match('/([0-9]{2}):([0-9]{2}):([0-9]{2})\.([0-9]{3})/', $string, $timeMathc);
        $parsedLog[$i]["time"] = $timeMathc[0];

        $i++;
    }

    return $parsedLog;
}

function parseAbbreviations(array $abbreviations): array
{
    $data = [];
    $i=0;
    foreach ($abbreviations as $abbreviation){
        $separatedString=explode("_",$abbreviation);
        $data[$i]["abbreviation"]=$separatedString[0];
        $data[$i]["name"]=$separatedString[1];
        $data[$i]["team"]=$separatedString[2];

        $i++;
    }

    return $data;
}

$startLog = parseLogs($startLogData);

$endLog = parseLogs($endLogData);

$racersInfo =parseAbbreviations($abbreviationsData);

//print_r($startLog);
//echo "<hr>";
//print_r($endLog);
//echo "<hr>";
//print_r($racersInfo);


function getRacersRaceInfo($racersInfo, $startLog, $endLog): array
{
    $racerRaceInfo =[];
    $i=0;
    foreach ($racersInfo as $racerInfo) {
        $racerRaceInfo[$i]['name'] = $racerInfo['name'];
        $racerRaceInfo[$i]['team'] = $racerInfo['team'];
        $racerRaceInfo[$i]['abbreviation'] = $racerInfo['abbreviation'];
        foreach ($startLog as $racerStartTimeInfo) {
            if ($racerInfo["abbreviation"] == $racerStartTimeInfo['racer_abbreviation']) {
                $racerRaceInfo[$i]['start_time'] = $racerStartTimeInfo['time'];
                $racerRaceInfo[$i]['start_date'] = $racerStartTimeInfo['date'];
            }
        }
        foreach ($endLog as $racerEndTimeInfo) {
            if ($racerInfo["abbreviation"] == $racerEndTimeInfo['racer_abbreviation']) {
                $racerRaceInfo[$i]['end_time'] = $racerEndTimeInfo['time'];
                $racerRaceInfo[$i]['end_date'] = $racerEndTimeInfo['date'];
            }
        }
        $racerRaceInfo[$i]['time_result'] =getTimeResult(
            $racerRaceInfo[$i]['start_time'],
            $racerRaceInfo[$i]['end_time'],
            $racerRaceInfo[$i]['start_date'],
            $racerRaceInfo[$i]['end_date']
        ) ;
        $i++;
    }

    return $racerRaceInfo;
}
echo "<pre>";
print_r(getRacersRaceInfo($racersInfo, $startLog, $endLog));
echo "</pre>";

function getTimeResult(string $startTime, string $endTime, string $startDate, string $endDate)
{

    $startD = DateTime::createFromFormat('Y-m-d H:i:s.u', $startDate . " " . $startTime);
    $finishD = DateTime::createFromFormat('Y-m-d H:i:s.u', $endDate . " " . $endTime);
    $startTimestamp = $startD->getTimestamp() . '.' . $startD->format('u');
    $finishTimestamp = $finishD->getTimestamp() . '.' . $finishD->format('u');

    $resultTimestamp = round($finishTimestamp - $startTimestamp, 3);
    $e = explode(".", $resultTimestamp);

    $timeResult = gmdate("i:s", $e[0]) . '.' . $e[1];

    return $timeResult;
}


//echo getTimeResult('12:07:26.122', '12:11:29.834', '2018-05-24','2018-05-24');
