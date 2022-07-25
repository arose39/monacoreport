<?php declare(strict_types=1);

namespace MonacoReport\Parsers;

use InvalidArgumentException;
use function PHPUnit\Framework\fileExists;

class AbbreviationsParser
{
    public function parse(string $abbreviationsPath): array
    {
        $abbreviationsData = $this->getDataFromFiles($abbreviationsPath);
        $racersInfo = [];
        $i = 0;
        foreach ($abbreviationsData as $abbreviation) {
            $separatedString = explode("_", $abbreviation);
            $racersInfo[$i]["abbreviation"] = $separatedString[0];
            $racersInfo[$i]["name"] = $separatedString[1];
            $racersInfo[$i]["team"] = trim($separatedString[2]);
            $i++;
        }

        return $racersInfo;
    }

    // Не использовал files_get_content(), так как удобнее считывать построчно, а не целиком
    private function getDataFromFiles(string $path): array
    {
        if (!file_exists($path)) {
            throw new InvalidArgumentException("$path file is not exist");
        }
        $fp = fopen($path, 'r');
        $data = [];
        for ($i = 0; $fileString = fgets($fp); $i++) {
            $data[$i] = $fileString;
        }
        fclose($fp);

        return $data;
    }
}
