<?php

declare(strict_types=1);

namespace App\Controller;

use App\Trip\Destination\Fetcher\DestinationFetcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(private readonly DestinationFetcherInterface $fetcher)
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $destinations = $this->fetcher->getDestinations();

        return $this->render('home/index.html.twig', [
            'destinations' => $destinations,
        ]);
    }
}
