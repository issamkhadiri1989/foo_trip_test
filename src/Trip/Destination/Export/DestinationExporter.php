<?php

declare(strict_types=1);

namespace App\Trip\Destination\Export;

use App\Exception\DestinationDirectoryNotFoundException;
use App\Trip\Destination\Export\Strategy\ExportStrategyInterface;
use Symfony\Component\Filesystem\Filesystem;

class DestinationExporter
{
    public function __construct(private readonly Filesystem $filesystem)
    {
    }

    /**
     * @throws DestinationDirectoryNotFoundException
     */
    public function exportToFile(
        array $destinations,
        ExportStrategyInterface $strategy,
        string $filePath,
        bool $overrideIfExists = false
    ): void {
        $dirname = \dirname($filePath);

        if (false === $this->filesystem->exists($dirname)) {
            throw new DestinationDirectoryNotFoundException();
        }

        if (true === $overrideIfExists) {
            $this->filesystem->remove($filePath);
        }

        // export the content depending on which strategy we selected.
        $content = $strategy->export($destinations);

        // write content to file
        $this->filesystem->dumpFile($filePath, $content);
    }
}
