<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Destination;
use App\Form\Type\DestinationType;
use App\Trip\Destination\Fetcher\DestinationFetcherInterface;
use App\Trip\Destination\Handler\Command\Doctrine\AddDestinationCommand;
use App\Trip\Destination\Handler\Command\Doctrine\DeleteDestinationCommand;
use App\Trip\Destination\Handler\Command\Doctrine\EditDestinationCommand;
use App\Trip\Destination\Handler\DestinationHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(name: 'admin_')]
#[IsGranted('ROLE_ADMIN')]
class DestinationAdminController extends AbstractController
{
    public function __construct(
        private readonly DestinationFetcherInterface $fetcher,
        private readonly DestinationHandlerInterface $handler,
    ) {
    }

    #[Route(path: '/destination/list', name: 'destination_list')]
    public function list(): Response
    {
        $destinations = $this->fetcher->getDestinations();

        return $this->render('admin/destination/list.html.twig', ['destinations' => $destinations]);
    }

    #[Route(path: '/destination/add', name: 'destination_add')]
    public function manage(Request $request, AddDestinationCommand $command): Response
    {
        $destination = new Destination();

        $form = $this->createForm(DestinationType::class, $destination);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // @todo Remove this and replace it with uploader
            $destination->setImage('https://picsum.photos/720/480');
            $this->handler->handle($destination, $command);

            return $this->redirectToRoute('admin_destination_list');
        }

        return $this->render('admin/destination/manage.html.twig', ['form' => $form]);
    }

    #[Route(path: '/destination/delete/{id}', name: 'destination_remove', methods: ['POST'])]
    public function delete(Destination $destination, DeleteDestinationCommand $command, Request $request): RedirectResponse
    {
        $csrfToken = $request->request->get('__token');

        if (!$this->isCsrfTokenValid('delete__destination__'.$destination->getId(), $csrfToken)) {
            throw new InvalidCsrfTokenException();
        }

        $this->handler->handle($destination, $command);

        return $this->redirectToRoute('admin_destination_list');
    }

    #[Route(path: '/destination/edit/{id}', name: 'destination_edit')]
    public function edit(Destination $destination, EditDestinationCommand $command, Request $request): Response
    {
        $form = $this->createForm(DestinationType::class, $destination);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handler->handle($destination, $command);

            return $this->redirectToRoute('admin_destination_list');
        }

        return $this->render('admin/destination/manage.html.twig', ['form' => $form]);
    }
}
