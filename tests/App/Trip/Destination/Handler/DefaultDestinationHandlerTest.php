<?php

namespace App\Tests\App\Trip\Destination\Handler;

use ApiPlatform\Metadata\Delete;
use App\Entity\Destination;
use App\Trip\Destination\Handler\Command\Doctrine\AddDestinationCommand;
use App\Trip\Destination\Handler\Command\Doctrine\DeleteDestinationCommand;
use App\Trip\Destination\Handler\Command\Doctrine\EditDestinationCommand;
use App\Trip\Destination\Handler\DefaultDestinationHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DefaultDestinationHandlerTest extends KernelTestCase
{
    protected EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        $this->entityManager = $container->get(EntityManagerInterface::class);
    }

    public function testAddDestinationCommand(): void
    {
        $container = static::getContainer();

        /** @var DefaultDestinationHandler $defaultHandler */
        $defaultHandler = $container->get(DefaultDestinationHandler::class);

        /** @var AddDestinationCommand $addCommand */
        $addCommand = $container->get(AddDestinationCommand::class);

        $destination = (new Destination())->setImage('test.png')
            ->setDescription('ipsum lorem dolore')
            ->setDuration(1)
            ->setName('ipsum')
            ->setPrice(100);

        $defaultHandler->handle($destination, $addCommand);

        // the $destination object should receive an ID
        $this->assertIsInt($destination->getId());

        $storedEntity = $this->entityManager
            ->getRepository(Destination::class)
            ->find($destination->getId());

        // compare the 2 instances. they should be exactly the same
        $this->assertEquals($destination, $storedEntity);
    }

    public function testEditDestinationCommand(): void
    {
        $container = static::getContainer();

        /** @var DefaultDestinationHandler $defaultHandler */
        $defaultHandler = $container->get(DefaultDestinationHandler::class);

        /** @var EditDestinationCommand $addCommand */
        $editCommand = $container->get(EditDestinationCommand::class);

        /** @var Destination $storedEntity */
        $destination = $this->entityManager
            ->getRepository(Destination::class)
            ->find(1);
        $destination->setPrice(0);

        $defaultHandler->handle($destination, $editCommand);

        /** @var Destination $storedEntity */
        $storedEntity = $this->entityManager
            ->getRepository(Destination::class)
            ->find(1);

        // compare the 2 instances. they should be exactly the same
        $this->assertEquals($destination, $storedEntity);
    }

    public function testDeleteDestinationCommand(): void
    {
        $container = static::getContainer();

        /** @var DefaultDestinationHandler $defaultHandler */
        $defaultHandler = $container->get(DefaultDestinationHandler::class);

        /** @var DeleteDestinationCommand $addCommand */
        $deleteCommand = $container->get(DeleteDestinationCommand::class);

        /** @var Destination $storedEntity */
        $destination = $this->entityManager
            ->getRepository(Destination::class)
            ->find(1);

        $defaultHandler->handle($destination, $deleteCommand);

        $storedEntity = $this->entityManager
            ->getRepository(Destination::class)
            ->find(1);

        // the object is no longer existing
        $this->assertNull($storedEntity);
    }
}