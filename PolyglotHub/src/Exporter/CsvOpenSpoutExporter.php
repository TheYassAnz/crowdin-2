<?php

namespace App\Exporter;

use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use App\Enum\CsvFormat;
use App\Exporter\ExporterInterface;
use App\Factory\CsvWriterFactory;

class CsvOpenSpoutExporter implements ExporterInterface
{
    public function __construct(private readonly CsvWriterFactory $factory)
    {
    }

    public function export(array $columnNames, array $data, CsvFormat $format): \SplFileInfo
    {
        $filePath = "export.{$format->extension()}";
        $rows = [Row::fromValues($columnNames, (new Style())->setFontBold())];

        foreach ($data as $row) {
            $rows[] = Row::fromValues($row);
        }

        $writer = $this->factory->fromCsvFormat($format);
        $writer->openToBrowser($filePath);
        $writer->addRows($rows);
        $writer->close();

        return new \SplFileInfo($filePath);
    }
}