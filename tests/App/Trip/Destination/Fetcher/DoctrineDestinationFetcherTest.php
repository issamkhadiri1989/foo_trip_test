<?php

declare(strict_types=1);

namespace App\Tests\App\Trip\Destination\Fetcher;

use App\DTO\Destination;
use App\Trip\Destination\Fetcher\ApiDestinationFetcher;
use App\Trip\Destination\Fetcher\DoctrineDestinationFetcher;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class DoctrineDestinationFetcherTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
    }

    /**
     * @dataProvider elementsDataProvider
     *
     * @throws ExceptionInterface
     */
    public function testFetchFromDatabase(int $id, string $name): void
    {
        $container = static::getContainer();

        /** @var DoctrineDestinationFetcher $fetcherService */
        $fetcherService = $container->get(DoctrineDestinationFetcher::class);

        $destinations = $fetcherService->getDestinations();

        $this->assertNotEmpty($destinations);

        // asserting that all items are of type DTO\Destination

        $child = \array_filter($destinations, fn (Destination $d) => $d->getId() === $id);

        $this->assertNotEmpty($child);

        // check that we have the same data in sqlite
        $this->assertEquals($id, $child[0]->getId());
        $this->assertEquals($name, $child[0]->getName());
    }

    public function testFetchFromApi(): void
    {
        $jsonResponse = \file_get_contents(__DIR__.'/response.json');

        $container = static::getContainer();

        /** @var SerializerInterface $serializer */
        $serializer = $container->get('serializer');

        $client = $this->getMockForAbstractClass(HttpClientInterface::class);

        $response = $this->getMockForAbstractClass(ResponseInterface::class);
        $response->method('getContent')
            ->willReturn($jsonResponse);

        $client->method('request')
            ->willReturn($response);

        $fetcher = new ApiDestinationFetcher($client, $serializer);

        $destinations = $fetcher->getDestinations();

        $this->assertNotEmpty($destinations);

        foreach ($destinations as $destination) {
            // check that we have the same data in sqlite
            $this->assertNotNull($destination->getId());
            $this->assertNotNull($destination->getName());
            $this->assertInstanceOf(Destination::class, $destination);
        }
    }

    public function elementsDataProvider(): array
    {
        return [
            [1, '3 Days Desert Tour From Marrakech To Merzouga Dunes & Camel Trek'],
        ];
    }
}
