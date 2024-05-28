<?php

declare(strict_types=1);

namespace App\Trip\Destination\Handler\Command\Doctrine;

use App\Trip\Destination\Handler\Command\CommandInterface;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractDoctrineCommand implements CommandInterface
{
    public function __construct(protected readonly EntityManagerInterface $manager)
    {
    }
}
