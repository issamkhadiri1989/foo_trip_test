<?php

declare(strict_types=1);

namespace App\Trip\Destination\Fetcher;

use App\DTO\Destination;

interface DestinationFetcherInterface
{
    /**
     * Get all trips.
     *
     * @return Destination[]
     */
    public function getDestinations(): array;
}
