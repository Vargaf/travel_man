<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zinio\Domain\TravelMan\Application\TravelManGetShortestPathUseCase;
use Zinio\Domain\TravelMan\Exceptions\TravelManGetShortestPathException;

class TravelManCommand extends Command
{
    // The name of the command (the part after "bin/console")
    protected static $defaultName = 'zinio:travel-man';

    private TravelManGetShortestPathUseCase $useCase;

    public function __construct(TravelManGetShortestPathUseCase $useCase)
    {
        $this->useCase = $useCase;
        
        parent::__construct();
    }

    protected function configure()
    {
        $this
        // the short description shown while running "php bin/console list"
        ->setDescription('Calculate the shortest path to our travel man.')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command calculates the shortest path to our travel man from the cities list on cities.txt file at db folder.')

        // configure an argument
        ->addArgument('maxNodesToForesight', InputArgument::OPTIONAL, 'The number of nodes to foreseight to find the neares path. The more the slower, but that not means to be more accurate.', 4)
    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Running the travel man algorithm, please wait.</info>');
        try {
            $maxNodesToForesight = $input->getArgument('maxNodesToForesight');
            $travelManPath = $this->useCase->execute($maxNodesToForesight);

            foreach($travelManPath->path() as $city) {
                $output->writeln('<info>' . $city->name() . '</info>');
            }

            return Command::SUCCESS;

        } catch(TravelManGetShortestPathException $error) {
            $output->writeln('<error>' . $error->getMessage() . '</error>');
            return Command::SUCCESS;
        }
    }
}