<?php

declare(strict_types=1);

namespace App\Trip\Destination\Fetcher;

use App\DTO\Destination as DestinationDTO;
use App\Entity\Destination;
use App\Repository\DestinationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

#[AsAlias]
final class DoctrineDestinationFetcher implements DestinationFetcherInterface
{
    public function __construct(private readonly EntityManagerInterface $manager)
    {
    }

    /**
     * @return DestinationDTO[]
     *
     * @throws ExceptionInterface
     */
    public function getDestinations(): array
    {
        /** @var DestinationRepository $repository */
        $repository = $this->manager->getRepository(Destination::class);

        return $repository->getDestinations();
    }
}
