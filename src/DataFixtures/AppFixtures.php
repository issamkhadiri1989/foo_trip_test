<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Destination;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\SerializerInterface;

final class AppFixtures extends Fixture
{
    private string $tripsJsonFile;

    public function __construct(
        private readonly SerializerInterface $serializer,
        #[Autowire('%kernel.project_dir%/data/trips.json')]
        string $tripsJsonFile,
    ) {
        $this->tripsJsonFile = $tripsJsonFile;
    }

    public function load(ObjectManager $manager): void
    {
        $content = \file_get_contents($this->tripsJsonFile);

        $trips = $this->serializer->deserialize($content, Destination::class.'[]', 'json');

        foreach ($trips as $trip) {
            $manager->persist($trip);
        }

        $manager->flush();
    }
}
