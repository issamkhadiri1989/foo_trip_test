<?php

declare(strict_types=1);

namespace App\Trip\Destination\Handler;

use App\Entity\Destination;
use App\Trip\Destination\Handler\Command\CommandInterface;

final class DefaultDestinationHandler extends AbstractDoctrineDestinationHandler
{
    public function handle(Destination $destination, CommandInterface $command): void
    {
        $command->execute($destination);
    }
}
