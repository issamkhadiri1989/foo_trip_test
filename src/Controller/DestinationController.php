<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Destination;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DestinationController extends AbstractController
{
    #[Route('/destination/{id}', name: 'app_destination')]
    public function index(Destination $destination): Response
    {
        return $this->render('destination/index.html.twig', [
            'destination' => $destination,
        ]);
    }
}
