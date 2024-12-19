<?php

namespace App\Factory;

use App\Enum\CsvFormat;
use OpenSpout\Reader\ReaderInterface;
use OpenSpout\Reader\CSV\Reader as CSVReader;
use OpenSpout\Reader\ODS\Reader as ODSReader;
use OpenSpout\Reader\XLSX\Reader as XLSXReader;

class CsvReaderFactory {
    public function fromCsvFormat(CsvFormat $format): ReaderInterface {
        return match ($format) {
            CsvFormat::CSV => new CSVReader(),
            CsvFormat::ODS => new ODSReader(),
            CsvFormat::XLSX => new XLSXReader(),
        };
    }
}