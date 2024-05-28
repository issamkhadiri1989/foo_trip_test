<?php

declare(strict_types=1);

namespace App\Trip\Destination\Handler\Command\Doctrine;

use App\Entity\Destination;
use App\Repository\DestinationRepository;

class EditDestinationCommand extends AbstractDoctrineCommand
{
    public function execute(Destination $entity): void
    {
        /** @var DestinationRepository $repository */
        $repository = $this->manager->getRepository(Destination::class);

        $repository->updateDestination($entity);
    }
}
