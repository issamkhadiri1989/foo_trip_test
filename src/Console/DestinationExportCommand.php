<?php

declare(strict_types=1);

namespace App\Console;

use App\Exception\DestinationDirectoryNotFoundException;
use App\Trip\Destination\Export\DestinationExporter;
use App\Trip\Destination\Export\Strategy\ExportStrategyInterface;
use App\Trip\Destination\Fetcher\ApiDestinationFetcher;
use App\Trip\Destination\Fetcher\DestinationFetcherInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsCommand(name: 'app:destination:export')]
class DestinationExportCommand extends Command
{
    private DestinationExporter $destinationExporter;
    private ExportStrategyInterface $strategy;
    private DestinationFetcherInterface $fetcher;

    public function __construct(
        DestinationExporter $destinationExporter,
        ExportStrategyInterface $strategy,
        #[Autowire(service: ApiDestinationFetcher::class)] DestinationFetcherInterface $fetcher,
    ) {
        $this->destinationExporter = $destinationExporter;
        $this->strategy = $strategy;
        $this->fetcher = $fetcher;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(
            'file_path',
            InputArgument::REQUIRED,
            description: 'The file path where to export the CSV content',
        );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $destinations = $this->fetcher->getDestinations();

        $filePath = $input->getArgument('file_path');

        $styler = new SymfonyStyle($input, $output);

        try {
            $this->destinationExporter->exportToFile($destinations, $this->strategy, $filePath);
        } catch (DestinationDirectoryNotFoundException $e) {
            $styler->error($e->getMessage());

            return Command::FAILURE;
        }

        $styler->success('Destinations exported to '.$filePath);

        return Command::SUCCESS;
    }
}
