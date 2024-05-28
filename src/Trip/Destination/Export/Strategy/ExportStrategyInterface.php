<?php

declare(strict_types=1);

namespace App\Trip\Destination\Export\Strategy;

/**
 * this interface represents the strategy to implement to export destinations.
 */
interface ExportStrategyInterface
{
    public function export(array $destinations): string;
}
