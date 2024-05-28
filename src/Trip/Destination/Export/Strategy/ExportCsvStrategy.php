<?php

declare(strict_types=1);

namespace App\Trip\Destination\Export\Strategy;

class ExportCsvStrategy extends AbstractSerializeExporter
{
    private const DELIMITER = ';';

    public function export(array $destinations): string
    {
        return $this->serializer->serialize($destinations, 'csv', context: [
            'csv_delimiter' => self::DELIMITER,
        ]);
    }
}
