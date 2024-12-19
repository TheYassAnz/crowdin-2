<?php 

namespace App\Exporter;

use App\Enum\CsvFormat;

interface ExporterInterface
{
    public function export(array $columnNames, array $data, CsvFormat $format): \SplFileInfo;
}