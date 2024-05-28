<?php

declare(strict_types=1);

namespace App\Trip\Destination\Handler\Command;

use App\Entity\Destination;

interface CommandInterface
{
    public function execute(Destination $entity): void;
}
