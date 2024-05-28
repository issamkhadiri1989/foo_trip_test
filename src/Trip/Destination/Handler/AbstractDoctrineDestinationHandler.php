<?php

declare(strict_types=1);

namespace App\Trip\Destination\Handler;

use Doctrine\ORM\EntityManagerInterface;

/**
 * This class will be extended by the handlers that will use Doctrine.
 */
abstract class AbstractDoctrineDestinationHandler implements DestinationHandlerInterface
{
    public function __construct(protected readonly EntityManagerInterface $manager)
    {
    }
}
