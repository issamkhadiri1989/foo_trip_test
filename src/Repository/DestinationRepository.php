<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\Destination as DestinationDTO;
use App\Entity\Destination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * @extends ServiceEntityRepository<Destination>
 */
class DestinationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private readonly DenormalizerInterface $serializer)
    {
        parent::__construct($registry, Destination::class);
    }

    /**
     * This function is equivalent to findAll(). The purpose here is probably to create a custom query.
     *
     * @throws ExceptionInterface
     */
    public function getDestinations(): array
    {
        $results = $this->createQueryBuilder('d')
            ->select('d.id, d.name, d.description, d.price, d.duration, d.image')
            ->getQuery()
            ->getArrayResult();

        return $this->serializer->denormalize($results, DestinationDTO::class.'[]');
    }

    public function updateDestination(Destination $entity): void
    {
        $this->doSaveInstance($entity, null === $entity->getId());
    }

    public function revokeDestination(Destination $entity): void
    {
        $this->getEntityManager()->remove($entity);

        $this->doSaveInstance($entity);
    }

    private function doSaveInstance(Destination $entity, bool $mustPersist = false)
    {
        $manager = $this->getEntityManager();

        if (true === $mustPersist) {
            $manager->persist($entity);
        }

        $manager->flush();
    }
}
