<?php

namespace App\Enum;

enum CsvFormat: string
{
    case CSV = 'csv';
    case XLSX = 'xlsx';
    case ODS = 'ods';

    public function contentType(): string
    {
        return match ($this) {
            self::CSV => 'text/csv',
            self::XLSX => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            self::ODS => 'application/vnd.oasis.opendocument.spreadsheet',
        };
    }

    public function extension(): string
    {
        return match ($this) {
            self::CSV => 'csv',
            self::XLSX => 'xlsx',
            self::ODS => 'ods',
        };
    }

    public static function fromExtension(?string $extension): self

    {
        return match ($extension) {
            'csv' => self::CSV,
            'xlsx' => self::XLSX,
            'ods' => self::ODS,
            default => self::CSV,
        };
    }
}
