<?php

declare(strict_types=1);

namespace App\Trip\Destination\Export\Strategy;

use Symfony\Component\Serializer\SerializerInterface;

/**
 * this class is to inherit only for the strategies that require the serializer component.
 */
abstract class AbstractSerializeExporter implements ExportStrategyInterface
{
    public function __construct(protected readonly SerializerInterface $serializer)
    {
    }
}
