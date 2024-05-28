<?php

declare(strict_types=1);

namespace App\Tests\App\Trip\Destination\Export;

use App\DTO\Destination as DestinationDTO;
use App\Entity\Destination;
use App\Exception\DestinationDirectoryNotFoundException;
use App\Trip\Destination\Export\DestinationExporter;
use App\Trip\Destination\Export\Strategy\ExportCsvStrategy;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DestinationExporterTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();

        if (\file_exists($file = __DIR__.'/export_target/file.csv')) {
     unlink($file);
    }
    }

    public function testExportToNonExistingDirectory(): void
    {
        $container = static::getContainer();

        /** @var DestinationExporter $exporter */
        $exporter = $container->get(DestinationExporter::class);

        /** @var ExportCsvStrategy $strategy */
        $strategy = $container->get(ExportCsvStrategy::class);

        /** @var DestinationDTO $destinations */
        $destinations = $container->get(EntityManagerInterface::class)
            ->getRepository(Destination::class)
            ->findAll();

        $this->expectException(DestinationDirectoryNotFoundException::class);

        $exporter->exportToFile($destinations, $strategy, '/var/www/some/non/existing/dir/file.csv');
    }

    public function testExportToExistingDirectory(): void
    {
        $container = static::getContainer();

        /** @var DestinationExporter $exporter */
        $exporter = $container->get(DestinationExporter::class);

        /** @var ExportCsvStrategy $strategy */
        $strategy = $container->get(ExportCsvStrategy::class);

        /** @var DestinationDTO $destinations */
        $destinations = $container->get(EntityManagerInterface::class)
            ->getRepository(Destination::class)
            ->findAll();

        $exportTarget = __DIR__.'/export_target/file.csv';

        $exporter->exportToFile($destinations, $strategy, $exportTarget);

        $this->assertFileExists($exportTarget);

        $exporter->exportToFile($destinations, $strategy, $exportTarget, true);

        $this->assertFileExists($exportTarget);
    }
}
