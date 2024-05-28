<?php

declare(strict_types=1);

namespace App\Trip\Destination\Fetcher;

use App\DTO\Destination;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class ApiDestinationFetcher implements DestinationFetcherInterface
{
    private HttpClientInterface $client;

    public function __construct(
        #[Target('$appDestinationApi')] HttpClientInterface $client,
        private readonly SerializerInterface $serializer,
    ) {
        $this->client = $client;
    }

    /**
     * @return Destination[]
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getDestinations(): array
    {
        $request = $this->client->request(
            options: [
                'headers' => ['Accept' => 'application/json'],
            ],
            method: 'GET',
            url: '/api/destinations'
        );

        $response = $request->getContent();

        return $this->serializer->deserialize($response, Destination::class.'[]', 'json');
    }
}
