<?php

namespace App\Factory;

use App\Enum\CsvFormat;
use OpenSpout\Writer\CSV\Writer as CSVWriter;
use OpenSpout\Writer\XLSX\Writer as XLSXWriter;
use OpenSpout\Writer\ODS\Writer as ODSWriter;

use OpenSpout\Writer\WriterInterface;

class CsvWriterFactory
{
    public function fromCsvFormat(CsvFormat $format): WriterInterface
    {
        return match ($format) {
            CsvFormat::CSV => new CSVWriter(),
            CsvFormat::XLSX => new XLSXWriter(),
            CsvFormat::ODS => new ODSWriter(),
        };
    }
}