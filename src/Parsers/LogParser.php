<?php declare(strict_types=1);

namespace MonacoReport\Parsers;

use InvalidArgumentException;
use function PHPUnit\Framework\fileExists;

class LogParser
{
    const DATE_TIME_PATTERN = '/([0-9]{4})-([0-9]{2})-([0-9]{2})_([0-9]{2}):([0-9]{2}):([0-9]{2})\.([0-9]{3})/';
    const RACER_ABBREVIATION_PATTERN = '/^.../';

    public function parse(string $logPath): array
    {
        $logData = $this->getDataFromFiles($logPath);
        $parsedLog = [];
        $i = 0;
        foreach ($logData as $string) {
            $racersAbreviationsMathc = [];
            preg_match($this::RACER_ABBREVIATION_PATTERN, $string, $racersAbreviationsMathc);
            if (isset($racersAbreviationsMathc[0])) {
                $parsedLog[$i]["racer_abbreviation"] = $racersAbreviationsMathc[0];
            }
            $dateTimeMatch = [];
            preg_match($this::DATE_TIME_PATTERN, $string, $dateTimeMatch);
            if (isset($dateTimeMatch[0])) {
                $parsedLog[$i]["date_time"] = $dateTimeMatch[0];
            }
            $i++;
        }

        return $parsedLog;
    }

    // Не использовал files_get_content(), так как удобнее считывать построчно, а не целиком
    private function getDataFromFiles(string $path): array
    {
        if (!file_exists($path)) {
            throw new InvalidArgumentException("$path file is not exist");
        } else {
            $fp = fopen($path, 'r');
            $data = [];
            for ($i = 0; $fileString = fgets($fp); $i++) {
                $data[$i] = $fileString;
            }
            fclose($fp);

            return $data;
        }
    }
}
