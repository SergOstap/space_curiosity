<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Nasa\NasaNearEarthObjectsSynchronizer;
use App\Service\Nasa\Request\NearEarthObjectsRequest;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class NasaSyncNearEarthObjectsCommand extends Command
{
    protected static $defaultName = 'app:nasa:sync-near-earth-objects';
    private NasaNearEarthObjectsSynchronizer $nasaAsteroidsNeoWsSynchronizer;

    public function __construct(NasaNearEarthObjectsSynchronizer $nasaAsteroidsNeoWsSynchronizer)
    {
        parent::__construct();
        $this->nasaAsteroidsNeoWsSynchronizer = $nasaAsteroidsNeoWsSynchronizer;
    }

    protected function configure()
    {
        $this
            ->setDescription('Synchronizes NASA Asteroids NeoWs')
            ->addArgument(
                'startDate',
                InputArgument::OPTIONAL,
                'Starting date for asteroid search. Format YYYY-MM-DD'
            )
            ->addArgument(
                'endDate',
                InputArgument::OPTIONAL,
                'Ending date for asteroid search. Format YYYY-MM-DD'
            )
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            if ($input->getArgument('startDate')) {
                $startDate = \DateTime::createFromFormat('Y-m-d', $input->getArgument('startDate'));
            } else {
                $startDate = new \DateTime('now -2 days');
            }
            if ($input->getArgument('endDate')) {
                $endDate = \DateTime::createFromFormat('Y-m-d', $input->getArgument('endDate'));
            } else {
                $endDate = new \DateTime('now');
            }
        } catch (\Exception $exception) {
            $io->error('Invalid date format');
            throw $exception;
        }

        $this->nasaAsteroidsNeoWsSynchronizer->synchronize(new NearEarthObjectsRequest($startDate, $endDate));

        $io->success('Successfully synchronized');

        return 0;
    }
}
