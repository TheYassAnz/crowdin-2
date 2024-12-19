<?php

namespace App\Importer;

use App\Enum\CsvFormat;
use App\Factory\CsvReaderFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CsvOpenSpoutImporter implements ImporterInterface
{
    public function __construct(private readonly CsvReaderFactory $factory) {}

    public function import(UploadedFile $file): array
    {
        $data =  [];
        $extension = $file->guessExtension();
        $format = CsvFormat::fromExtension($extension);
        $reader = $this->factory->fromCsvFormat($format);

        $reader->open($file->getPathname());

        /** @var SheetInterface $sheet */
        foreach ($reader->getSheetIterator() as $sheet) {
            /** @var Row $row */
            foreach ($sheet->getRowIterator() as $row) {
                $data[] = $row->toArray();
            }
        }
        return $data;
    }
}