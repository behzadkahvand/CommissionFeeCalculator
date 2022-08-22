<?php

namespace App\Services\Utils;

use http\Exception\InvalidArgumentException;

class CsvReaderService
{
    public function read(string $filePath): iterable
    {
        if (!is_array($csvFile = file($filePath))) {
            throw new InvalidArgumentException("File is not valid!");
        }

        foreach ($csvFile as $line) {
            yield str_getcsv($line);
        }
    }
}
